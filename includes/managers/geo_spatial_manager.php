<?php

namespace Mappa;

require_once __DIR__ . '/../php_functions/array_flatten.php';
require_once __DIR__ . '/../constants.php';
require_once 'post_manager.php';
require_once 'geo_category_manager.php';
require_once 'geo_category_group_manager.php';
require_once __DIR__ . '/../helpers/manager_helper.php';

class GeoSpatialManager extends PostManager
{
    public function __construct($mappaObject, $postType, $options)
    {
        return parent::__construct($mappaObject, $postType, $options);
    }

    public function findCategoryTerms(): array
    {
        $termsQuery = GeoCategoryManager::findByIds(
            $this->mappaObject['category_ids']
        );

        return $termsQuery->terms ?? [];
    }

    public function findCategoryGroupTerms($categoryTerms): array
    {
        $categoryGroupTermIds = array_unique(
            array_flatten(
                array_map(function ($term) {
                    return \get_term_meta(
                        $term->term_id,
                        '_mappa_category_group_ids',
                        true
                    );
                }, $categoryTerms)
            )
        );

        $termsQuery = GeoCategoryGroupManager::findByIds($categoryGroupTermIds);

        return $termsQuery->terms ?? [];
    }

    public function findByData(): \WP_Query
    {
        return new \WP_Query([
            'post_type'   => $this->postType,
            'post_status' => 'any',
            'meta_query'  => [
                ['key' => '_mappa_id', 'value' => $this->mappaObject['id']]
            ]
        ]);
    }

    public function postParams() : array
    {
        $postCreatedDate = ManagerHelper::datetimeToWordpress(
            $this->mappaObject['created_at']
        );
        $postModifiedDate = ManagerHelper::datetimeToWordpress(
            $this->mappaObject['updated_at']
        );

        $attrs = [
            'post_date'         => $postCreatedDate,
            'post_date_gmt'     => $postCreatedDate,
            'post_title'        => $this->mappaObject['title_translations'][$this->options['language']],
            'post_name'         => '',
            'post_status'       => 'publish',
            'post_type'         => $this->postType,
            'post_modified'     => $postModifiedDate,
            'post_modified_gmt' => $postModifiedDate,
            'post_author'       => $this->options['post_author_id'],
            'post_content'      => $this->mappaObject['description']['content_translations'][
                    $this->options['language']
                ] ?? '',
            'post_excerpt' => $this->mappaObject['description']['intro_translations'][
                    $this->options['language']
                ] ?? ''
        ];

        $metaAttrs = [
            '_mappa_id'         => $this->mappaObject['id'],
            '_mappa_updated_at' => $this->mappaObject['updated_at'],
        ];

        $categoryTerms = $this->findCategoryTerms();
        $taxAttrs      = [
            MAPPA_GEO_CATEGORY => array_map(function ($term) {
                return $term->term_id;
            }, $categoryTerms),
            MAPPA_GEO_CATEGORY_GROUP => array_map(
                function ($term) {
                    return $term->term_id;
                },
                empty($categoryTerms)
                    ? []
                    : $this->findCategoryGroupTerms($categoryTerms)
            )
        ];

        if (!empty($this->mappaObject['images'])) {
            $metaAttrs['gallery_image_ids'] = [];

            $sortedImages = $this->mappaObject['images'];
            usort($sortedImages, function ($a, $b) {
                return $a['position'] - $b['position'];
            });

            foreach ($sortedImages as $dataImage) {
                $imageManager = new MediaDocumentManager($dataImage, $this->options);
                $image        = $imageManager->process();
                if (!isset($image->ID)) {
                    continue;
                }
                $metaAttrs['gallery_image_ids'][] = $image->ID;
            }

            $metaAttrs['_thumbnail_id'] = $metaAttrs['gallery_image_ids'][0];
        }

        $attrs['meta_input'] = $metaAttrs;
        $attrs['tax_input']  = $taxAttrs;

        return $attrs;
    }
}
