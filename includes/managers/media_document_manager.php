<?php

namespace Mappa;

require_once __DIR__ . '/../constants.php';
require_once __DIR__ . '/../helpers/manager_helper.php';

class MediaDocumentManager
{
    public static function findByIds(array $ids): \WP_Term_Query
    {
        return new \WP_Term_Query([
            'post_type'   => 'attachment',
            'post_status' => 'any',
            'meta_query'  => [['key' => '_mappa_id', 'value' => $ids]]
        ]);
    }

    public $mappaObject;
    public $action;
    public $isActionSkipped = false;
    public $forceUpdate     = false;

    public function __construct($mappaObject)
    {
        $this->mappaObject = $mappaObject;
    }

    public function process(): \WP_Post
    {
        $postsQuery           = $this->findByData();
        $existedPost          = $postsQuery->posts[0] ?? null;
        $isPostExisted        = !is_null($existedPost);
        $existedPostMeta      = $isPostExisted ? \get_post_meta($existedPost->ID) : null;
        $isMappaObjectDeleted = !is_null($this->mappaObject['deleted_at']);

        # create
        if (!$isPostExisted && !$isMappaObjectDeleted) {
            return $this->createAttachment();
        }

        # update
        if ($isPostExisted && !$isMappaObjectDeleted) {
            # skip same version
            $modifiedDate = $existedPostMeta['_mappa_updated_at'][0] ?? null;

            if ($modifiedDate === $this->mappaObject['updated_at']) {
                if (!$this->forceUpdate) {
                    $this->action          = 'update';
                    $this->isActionSkipped = true;
                    return $existedPost;
                }
            }

            return $this->updateAttachment($existedPost);
        }

        # destroy
        if ($isPostExisted && $isMappaObjectDeleted) {
            return $this->destroyAttachment($existedPost);
        } else {
            $this->action          = 'destroy';
            $this->isActionSkipped = true;
        }

        return $existedPost;
    }

    public function findByData(): \WP_Query
    {
        return new \WP_Query([
            'post_type'   => 'attachment',
            'post_status' => 'any',
            'meta_query'  => [
                ['key' => '_mappa_id', 'value' => $this->mappaObject['id']]
            ]
        ]);
    }

    private function createAttachment(): \WP_Post
    {
        $this->action = 'create';

        $postParams = $this->postParams();

        $url      = MAPPA_URL . urldecode($this->mappaObject['full_file']['path']);
        $fileName = $postParams['_filename'];
        $http     = new \WP_Http();
        $response = $http->request($url, ['timeout' => 60]);
        $dirDate  = ManagerHelper::formatDate($this->mappaObject['created_at'], 'Y/m');
        if ($response['response']['code'] != 200) {
            return false;
        }

        $upload = \wp_upload_bits($fileName, null, $response['body'], $dirDate);
        if (!empty($upload['error'])) {
            return false;
        }

        $filePath = $upload['file'];

        // Check the type of file. We'll use this as the 'post_mime_type'.
        $fileType = \wp_check_filetype($fileName, null);

        // Get the path to the upload directory.
        $wpUploadDir = \wp_upload_dir($dirDate);

        // Prepare an array of post data for the attachment.
        $postMeta = array_merge($postParams, [
            'guid'           => $wpUploadDir['url'] . '/' . $fileName,
            'post_mime_type' => $fileType['type']
        ]);

        if (empty($postMeta['post_title'])) {
            $postMeta['post_title'] = preg_replace("/[\-_]/", " ", $fileName);
        }

        // Insert the attachment.
        $attachmentId = \wp_insert_attachment($postMeta, $filePath, $this->options['post_parent_post_id'] ?? 0, true);

        require_once ABSPATH . 'wp-admin/includes/image.php';

        // Generate the metadata for the attachment, and update the database record.
        $attachmentData = \wp_generate_attachment_metadata(
            $attachmentId,
            $filePath
        );
        \wp_update_attachment_metadata($attachmentId, $attachmentData);

        return \get_post($attachmentId, 'attachment');
    }

    private function updateAttachment($post): \WP_Post
    {
        $this->action = 'update';

        $postId = \wp_update_post(
            array_merge($this->postParams(), ['ID' => $post->ID])
        );

        return \get_post($postId);
    }

    private function destroyAttachment($post): \WP_Post
    {
        $this->action = 'destroy';

        return \wp_delete_attachment($post->ID);
    }

    private function postParams(): array
    {
        $postCreatedDate = ManagerHelper::datetimeToWordpress(
            $this->mappaObject['created_at']
        );
        $postModifiedDate = ManagerHelper::datetimeToWordpress(
            $this->mappaObject['updated_at']
        );

        $pathInfo = pathinfo($this->mappaObject['full_file']['path']);

        $attrs = [
            'post_date'     => $postCreatedDate,
            'post_date_gmt' => $postCreatedDate,
            'post_title'    => $this->mappaObject['description']['title_translations'][
                    $this->options['language']
                ] ?? preg_replace("/[\-_]/", " ", $pathInfo['filename']),
            'post_status'       => 'inherit',
            'post_modified'     => $postModifiedDate,
            'post_modified_gmt' => $postModifiedDate,
            'post_author'       => $this->options['post_author_id'],
            '_filename'         => $fileName['basename']
        ];

        $metaAttrs = [
            '_mappa_id'         => $this->mappaObject['id'],
            '_mappa_updated_at' => $this->mappaObject['updated_at']
        ];

        if (
            $this->mappaObject['description']['source_translations'][
                $this->options['language']
            ]
        ) {
            $metaAttrs['source'] = $this->mappaObject['description']['source_translations'][
                    $this->options['language']
                ];
        }

        $attrs['meta_input'] = $metaAttrs;

        return $attrs;
    }
}
