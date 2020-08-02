<?php

namespace Mappa;

class PostManager
{
    public $mappaObject;
    public $postType;
    public $options;
    public $action;
    public $isActionSkipped = false;
    public $forceUpdate;

    public function __construct($mappaObject, $postType, $options)
    {
        $this->mappaObject = $mappaObject;
        $this->postType    = $postType;
        $this->options     = $options;
        $this->forceUpdate = $options['force_update'] ?? false;
    }

    public function postParams() : ?array
    {
        throw new Exception('Implement postParams in child class.');
    }

    public function process() : \WP_Post
    {
        $postsQuery           = $this->findByData();
        $existedPost          = $postsQuery->posts[0] ?? null;
        $isPostExisted        = !is_null($existedPost);
        $existedPostMeta      = $isPostExisted ? \get_post_meta($existedPost->ID) : null;
        $isMappaObjectDeleted = !is_null($this->mappaObject['deleted_at']);

        # create
        if (!$isPostExisted && !$isMappaObjectDeleted) {
            return $this->createPost();
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

            return $this->updatePost($existedPost);
        }

        # destroy
        if ($isPostExisted && $isMappaObjectDeleted) {
            return $this->destroyPost($existedPost);
        } else {
            $this->action          = 'destroy';
            $this->isActionSkipped = true;
        }

        return $existedPost;
    }

    public function findByData() : \WP_Query
    {
        return new \WP_Query([
            'post_type'   => $this->postType,
            'post_status' => 'any',
            'meta_query'  => [
                ['key' => '_mappa_id', 'value' => $this->mappaObject['id']]
            ]
        ]);
    }

    public function createPost() : \WP_Post
    {
        $this->action = 'create';

        $postParams = $this->postParams();
        $postId     = \wp_insert_post($postParams, true);

        if (!empty($postParams['meta_input']['gallery_image_ids'])) {
            foreach ($postParams['meta_input']['gallery_image_ids'] as $attachmentId) {
                self::updatePostAttributes($attachmentId, ['post_parent' => $postId]);
            }
        }

        return \get_post($postId);
    }

    public function updatePost(\WP_Post $post) : \WP_Post
    {
        $this->action = 'update';

        $postParams = $this->postParams();
        $postId     = self::updatePostAttributes($post->ID, $postParams);

        if (!empty($postParams['meta_input']['gallery_image_ids'])) {
            foreach ($postParams['meta_input']['gallery_image_ids'] as $attachmentId) {
                self::updatePostAttributes($attachmentId, ['post_parent' => $postId]);
            }
        }

        return \get_post($postId);
    }

    public function destroyPost(\WP_Post $post) : bool
    {
        $this->action = 'destroy';

        return \wp_delete_post($post->ID);
    }

    public static function updatePostAttributes(int $postId, array $postParams) : int
    {
        return \wp_update_post(array_merge($postParams, ['ID' => $postId]), true);
    }
}
