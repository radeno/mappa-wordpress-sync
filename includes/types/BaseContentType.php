<?php

namespace Mappa\Types;

abstract class BaseContentType
{
    // inits Content Type (Post Type, Taxonomy and Meta Boxes)
    public static function initContentType()
    {
        static::initPostType();
        static::initTaxonomy();
        static::initPostTypeMeta();
        static::initTaxonomyMeta();
        static::initFiltersActions();
        static::initPostAdminTaxonomyFilter();
    }

    public static function initPostType()
    {
    }

    public static function initTaxonomy()
    {
    }

    public static function initPostTypeMeta()
    {
    }

    public static function initTaxonomyMeta()
    {
    }

    public static function initMetaBoxes()
    {
    }

    public static function initFiltersActions()
    {
    }

    public static function initPostAdminTaxonomyFilter()
    {
        if (!defined("static::POST_TYPE_NAME")) {
            return;
        }

        \add_action(
            'restrict_manage_posts',
            function ($post_type, $which) {
                if (static::POST_TYPE_NAME !== $post_type || !defined("static::TAXONOMY_NAME")) {
                    return;
                }

                $taxonomy_slug = static::TAXONOMY_NAME;
                $taxonomy = \get_taxonomy($taxonomy_slug);
                $selected = '';

                if (isset($_REQUEST[$taxonomy_slug])) {
                    $selected = $_REQUEST[$taxonomy_slug]; //in case the current page is already filtered
                }

                \wp_dropdown_categories([
                    'show_option_all' => __("Show All", 'city-base') . ' ' . __($taxonomy->label, 'city-base'),
                    'taxonomy'        => $taxonomy_slug,
                    'name'            => $taxonomy_slug,
                    'value_field'     => 'slug',
                    'orderby'         => 'name',
                    'selected'        => $selected,
                    'hierarchical'    => true,
                    'show_count'      => true,
                    'hide_empty'      => false
                ]);
            },
            10,
            2
        );
    }

    public static function postTypeAssociationName($prefix = '')
    {
        if (!defined("static::POST_TYPE_NAME")) {
            return null;
        }

        return ($prefix ? $prefix . '_' : '') . static::POST_TYPE_NAME . '_id';
    }
}
