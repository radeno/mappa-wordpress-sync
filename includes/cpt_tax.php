<?php

namespace Mappa;

require_once 'constants.php';
use helper\CapabilityHelper;

function cptTaxInit()
{
    $defaultGeoCpt = [
        'public'   => true,
        'supports' => [
            'title',
            'editor',
            'author',
            'thumbnail',
            'revisions',
            'excerpt',
            'custom-fields'
        ],
        'has_archive'  => true,
        'rewrite'      => true,
        'show_in_rest' => true
    ];

    \register_taxonomy(
        MAPPA_GEO_CATEGORY_GROUP,
        [MAPPA_GEO_PLACE, MAPPA_GEO_ROUTE],
        [
            'public'            => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'labels'            => [
                'name'          => __('Category Groups', 'mappa'),
                'singular_name' => __('Category Group', 'mappa')
            ],
            'hierarchical' => true,
            'rewrite'      => true,
            'capabilities' => [
                'manage_terms' => 'manage_' . MAPPA_GEO_CATEGORY_GROUP . 's',
                'edit_terms'   => 'edit_' . MAPPA_GEO_CATEGORY_GROUP . 's',
                'delete_terms' => 'delete_' . MAPPA_GEO_CATEGORY_GROUP . 's',
                'assign_terms' => 'assign_' . MAPPA_GEO_CATEGORY_GROUP . 's'
            ]
        ]
    );

    \register_taxonomy(
        MAPPA_GEO_CATEGORY,
        [MAPPA_GEO_PLACE, MAPPA_GEO_ROUTE],
        [
            'public'            => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'labels'            => [
                'name'          => __('Categories', 'mappa'),
                'singular_name' => __('Category', 'mappa')
            ],
            'hierarchical' => true,
            'rewrite'      => true,
            'capabilities' => [
                'manage_terms' => 'manage_' . MAPPA_GEO_CATEGORY . 's',
                'edit_terms'   => 'edit_' . MAPPA_GEO_CATEGORY . 's',
                'delete_terms' => 'delete_' . MAPPA_GEO_CATEGORY . 's',
                'assign_terms' => 'assign_' . MAPPA_GEO_CATEGORY . 's'
            ]
        ]
    );

    \register_post_type(
        MAPPA_GEO_PLACE,
        array_merge(
            $defaultGeoCpt,
            [
                'labels' => [
                    'name'          => __('Places', 'mappa'),
                    'singular_name' => __('Place', 'mappa'),
                    'archives'      => __('Places', 'mappa')
                ],
                'capability_type' => [MAPPA_GEO_PLACE, MAPPA_GEO_PLACE . 's'],
                'taxonomies'      => [MAPPA_GEO_CATEGORY, MAPPA_GEO_CATEGORY_GROUP]
            ],
        )
    );

    \register_post_type(
        MAPPA_GEO_ROUTE,
        array_merge(
            $defaultGeoCpt,
            [
                'labels' => [
                    'name'          => __('Trails', 'mappa'),
                    'singular_name' => __('Trail', 'mappa'),
                    'archives'      => __('Trails', 'mappa')
                ],
                'capability_type' => [MAPPA_GEO_ROUTE, MAPPA_GEO_ROUTE . 's'],
                'taxonomies'      => [MAPPA_GEO_CATEGORY, MAPPA_GEO_CATEGORY_GROUP]
            ]
        )
    );

    \register_post_type(
        MAPPA_MESSAGE_EVENT,
        array_merge(
            $defaultGeoCpt,
            [
                'supports' => array_merge($defaultGeoCpt['supports'], ['sticky_posts']),
                'labels' => [
                    'name'          => __('Events', 'mappa'),
                    'singular_name' => __('Event', 'mappa'),
                    'archives'      => __('Events', 'mappa')
                ],
                'capability_type' => [MAPPA_MESSAGE_EVENT, MAPPA_MESSAGE_EVENT . 's'],
            ]
        )
    );
}

\add_action('init', 'Mappa\cptTaxInit', 15);

// Allow site admins to access custom post types and taxonomies
function addCaps()
{
    $role = 'administrator';

    foreach ([MAPPA_GEO_PLACE, MAPPA_GEO_ROUTE, MAPPA_MESSAGE_EVENT] as $contentType) {
        CapabilityHelper::addPostTypeCapabilities(
            [$contentType, $contentType . 's'],
            $role
        );
    }

    foreach ([MAPPA_GEO_CATEGORY, MAPPA_GEO_CATEGORY_GROUP] as $contentType) {
        CapabilityHelper::addTaxonomyCapabilities([$contentType, $contentType . 's'], $role);
    }
}
\add_action('admin_init', 'Mappa\addCaps');
