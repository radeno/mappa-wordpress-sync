<?php

namespace Mappa\Types;

require_once "./BaseContentType.php"

class GeoPlace extends BaseContentType
{
    const POST_TYPE_NAME        = 'geo_place';
    const POST_TYPE_PLURAL_NAME = 'geo_places';
    const TAXONOMY_NAME         = 'deputy_category';
    const TAXONOMY_PLURAL_NAME  = 'deputy_categories';

    public static function initPostType()
    {
        $labels = [
            'name'                  => __('Places', 'mappa'),
            'singular_name'         => __('Place', 'mappa'),
            'add_new'               => _x('Add New', 'Place', 'mappa'),
            'add_new_item'          => __('Add New Place', 'mappa'),
            'edit_item'             => __('Edit Place', 'mappa'),
            'new_item'              => __('New Place', 'mappa'),
            'view_item'             => __('View Place', 'mappa'),
            'view_items'            => __('View Place', 'mappa'),
            'search_items'          => __('Search Place', 'mappa'),
            'not_found'             => __('No places found', 'mappa'),
            'not_found_in_trash'    => __('No places found in Trash', 'mappa'),
            'all_items'             => __('All Places', 'mappa'),
            'archives'              => __('Places', 'mappa'),
            'featured_image'        => __('Image', 'mappa'),
            'set_featured_image'    => __('Set image', 'mappa'),
            'remove_featured_image' => __('Remove image', 'mappa'),
            'use_featured_image'    => __('Use image', 'mappa')
        ];

        $args = [
            'labels'          => $labels,
            'public'          => true,
            'show_ui'         => true,
            'show_in_menu'    => true,
            'show_in_rest'    => true,
            'rest_base'       => self::POST_TYPE_PLURAL_NAME,
            'query_var'       => true,
            'rewrite'         => true,
            'capability_type' => [self::POST_TYPE_NAME, self::POST_TYPE_PLURAL_NAME],
            'map_meta_cap'    => true,
            'has_archive'     => true,
            'menu_position'   => 33,
            'menu_icon'       => 'dashicons-businessman',
            'supports'        => ['title', 'editor', 'thumbnail', 'author', 'revisions', 'custom-fields'],
            'taxonomies'      => [self::TAXONOMY_NAME],
        ];

        \register_post_type(self::POST_TYPE_NAME, $args);
    }

    public static function initTaxonomy()
    {
        $labels = [
            'name'          => __('Deputy Categories', 'city-base'),
            'singular_name' => __('Deputy Category', 'city-base'),
            'search_items'  => __('Search Categories', 'city-base'),
            'all_items'     => __('All Categories', 'city-base'),
            'edit_item'     => __('Edit Category', 'city-base'),
            'update_item'   => __('Update Category', 'city-base'),
            'add_new_item'  => __('Add New Category', 'city-base'),
            'new_item_name' => __('New Category Name', 'city-base'),
            'not_found'     => __('No categories found', 'city-base'),
        ];

        $args = [
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_in_rest'      => true,
            'rest_base'         => self::TAXONOMY_PLURAL_NAME,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => true,
            'capabilities'      => [
                'manage_terms' => 'manage_' . self::TAXONOMY_PLURAL_NAME,
                'edit_terms'   => 'edit_' . self::TAXONOMY_PLURAL_NAME,
                'delete_terms' => 'delete_' . self::TAXONOMY_PLURAL_NAME,
                'assign_terms' => 'assign_' . self::TAXONOMY_PLURAL_NAME,
            ],
        ];

        \register_taxonomy(
            self::TAXONOMY_NAME,
            self::POST_TYPE_NAME,
            $args
        );
    }

    public static function initPostTypeMeta()
    {
        $metaFields = [
            'academic_title_before' => ['type' => 'string', 'single' => true, 'show_in_rest' => true],
            'academic_title_after'  => ['type' => 'string', 'single' => true, 'show_in_rest' => true],
            'club'                  => ['type' => 'string', 'single' => true, 'show_in_rest' => true],
            'phone'                 => ['type' => 'string', 'single' => true, 'show_in_rest' => true],
            'email'                 => ['type' => 'string', 'single' => true, 'show_in_rest' => true],
        ];

        foreach ($metaFields as $key => $args) {
            \register_post_meta(
                self::POST_TYPE_NAME,
                $key,
                $args
            );
        }
    }

    public static function initMetaBoxes($meta_boxes)
    {
        $meta_box = [
            'id'         => 'deputy_parameters',
            'title'      => __('Deputy Parameters', 'city-base'),
            'post_types' => [self::POST_TYPE_NAME],
            'autosave'   => true,
            'revision'   => true,
            'fields'     => [
                [
                    'id'   => 'academic_title_before',
                    'name' => __('Academic title before', 'city-base'),
                    'type' => 'text',
                ],
                [
                    'id'   => 'academic_title_after',
                    'name' => __('Academic title after', 'city-base'),
                    'type' => 'text',
                ],
                [
                    'id'   => 'club',
                    'name' => __('Club', 'city-base'),
                    'type' => 'text',
                ],
                [
                    'id'                => 'phone',
                    'name'              => __('Phone (international no spaces)', 'city-base'),
                    'type'              => 'text',
                    'label_description' => __('Eg. +421903123456', 'city-base'),
                ],
                [
                    'id'   => 'email',
                    'name' => __('Email', 'city-base'),
                    'type' => 'email',
                ],
            ],
        ];

        $meta_boxes[] = $meta_box;
        return $meta_boxes;
    }
}
