<?php

namespace Mappa;

/**
 * @package   MAPPA
 * @author    Choco3web
 * @license
 * @link
 * @copyright
 *
 * Plugin Name:       MAPPA Framework
 * Description:       MAPPA Synchronization Tool
 * Version:           1.0.6
 * Author:            Choco3web
 * Author URI:        choco3web.eu
 * Text Domain:       mappa
 * License:
 * License URI:
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
    die();
}

require_once 'includes/constants.php';
require_once 'includes/cpt_tax.php';
require_once 'includes/repositories/batch_api_repository.php';
require_once 'includes/repositories/geo_places_api_repository.php';
require_once 'includes/repositories/geo_routes_api_repository.php';
require_once 'includes/repositories/categories_api_repository.php';
require_once 'includes/repositories/message_events_api_repository.php';
require_once 'includes/repositories/category_groups_api_repository.php';
require_once 'includes/synchronizers/category_groups_synchronizer.php';
require_once 'includes/synchronizers/categories_synchronizer.php';
require_once 'includes/managers/manager_factory.php';
require_once 'includes/helpers/wp_mappa_helper.php';

function activatePlugin()
{
    \add_option('mappa_last_synchronization', null);

    if (!\wp_next_scheduled('mappa_synchronize_data')) {
        \wp_schedule_event(time(), 'twicedaily', 'mappa_synchronize_data');
    }
}

function deactivatePlugin()
{
    \wp_clear_scheduled_hook('mappa_synchronize_data');
}

// \register_activation_hook(__FILE__, 'Mappa\activatePlugin');
// \register_deactivation_hook(__FILE__, 'Mappa\deactivatePlugin');

function loadPluginTextdomain()
{
    \load_plugin_textdomain(
        'mappa',
        false,
        basename(\plugin_dir_path(__DIR__)) . '/languages/'
    );
}
\add_action('plugins_loaded', 'Mappa\loadPluginTextdomain', 1);

// \add_action('mappa_synchronize_data', 'Mappa\runSynchronization');

// function runSynchronization()
// {
//     prerunSynchronization();

//     $lastUpdatedAt = \get_option('mappa_batch_updated_at');

//     $apiResponse = new BatchApiRepository([
//         'updated_from' => $lastUpdatedAt
//     ]);

//     $defaultOptions = ['language' => substr(get_locale(),0, 2), 'post_author_id' => 1];

//     $processCategoryGroup = ManagerFactory::processData(
//         $apiResponse->getGeoCategoryGroups(),
//         MAPPA_GEO_CATEGORY_GROUP,
//         $defaultOptions
//     );

//     $processCategories = ManagerFactory::processData(
//         $apiResponse->getGeoCategories(),
//         MAPPA_GEO_CATEGORY,
//         $defaultOptions
//     );

//     $processGeoPlaces = ManagerFactory::processData(
//         $apiResponse->getGeoPlaces(),
//         MAPPA_GEO_PLACE,
//         $defaultOptions
//     );

//     if ($processCategories && $processCategoryGroup && $processGeoPlaces) {
//         \update_option(
//             'mappa_batch_updated_at',
//             $apiResponse->getMetadata()['updated_at']
//         );
//     }

//     afterrunSynchronization();
// }

function getLanguages() {
    if (function_exists('pll_languages_list')) {
        return pll_languages_list();
    }

    return [substr(get_locale(), 0, 2)];
}

function getDefaultLanguage() {
    if (function_exists('pll_default_language')) {
        return pll_default_language();
    }

    return substr(get_locale(), 0, 2);
}

function getCurrentLanguage() {
    if (function_exists('pll_current_language')) {
        return pll_current_language();
    }

    return substr(get_locale(), 0, 2);
}

function runManually()
{
    if (isset($_GET['process_data'])) {
        CategoryGroupsSynchronizer::call(['language' => substr(get_locale(), 0, 2), 'post_author_id' => 0]);
        CategoriesSynchronizer::call(['language' => substr(get_locale(), 0, 2), 'post_author_id' => 0]);
        echo 'Synchronized';
    }
}

\add_action('wp_loaded', 'Mappa\runManually');
