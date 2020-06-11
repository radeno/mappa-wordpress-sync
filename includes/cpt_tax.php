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
            'excerpt',
            'custom-fields'
        ],
        'has_archive'  => true,
        'rewrite'      => true,
        'show_in_rest' => false
    ];

    \register_taxonomy(
        MAPPA_GEO_CATEGORY_GROUP,
        [MAPPA_GEO_PLACE, MAPPA_GEO_ROUTE],
        [
            'public'            => true,
            'show_in_rest'      => false,
            'show_admin_column' => true,
            'labels'            => [
                'name'          => 'Geo Category Groups',
                'singular_name' => 'Geo Category Group'
            ],
            'hierarchical' => false,
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
            'show_in_rest'      => false,
            'show_admin_column' => true,
            'labels'            => [
                'name'          => 'Geo Categories',
                'singular_name' => 'Geo Category'
            ],
            'hierarchical' => false,
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
            [
                'labels' => [
                    'name'          => 'Geo Places',
                    'singular_name' => 'Geo Place',
                    'archives'      => 'Geo Places'
                ],
                'capability_type' => [MAPPA_GEO_PLACE, MAPPA_GEO_PLACE . 's'],
                'taxonomies'      => [MAPPA_GEO_CATEGORY, MAPPA_GEO_CATEGORY_GROUP]
            ],
            $defaultGeoCpt
        )
    );

    \register_post_type(
        MAPPA_GEO_ROUTE,
        array_merge(
            [
                'labels' => [
                    'name'          => 'Geo Routes',
                    'singular_name' => 'Geo Routes',
                    'archives'      => 'Geo Routes'
                ],
                'capability_type' => [MAPPA_GEO_ROUTE, MAPPA_GEO_ROUTE . 's'],
                'taxonomies'      => [MAPPA_GEO_CATEGORY, MAPPA_GEO_CATEGORY_GROUP]
            ],
            $defaultGeoCpt
        )
    );
}

\add_action('init', 'Mappa\cptTaxInit');

// Allow site admins to access custom post types and taxonomies
function addCaps()
{
    $role = 'administrator';

    foreach ([MAPPA_GEO_PLACE, MAPPA_GEO_ROUTE] as $contentType) {
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
