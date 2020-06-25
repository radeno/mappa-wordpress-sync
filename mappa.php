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
 * Version:           1.0.1
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
require_once 'includes/managers/manager_factory.php';

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

function runSynchronization()
{
    prerunSynchronization();

    $lastUpdatedAt = \get_option('mappa_batch_updated_at');

    $apiResponse = new BatchApiRepository([
        'updated_from' => $lastUpdatedAt
    ]);

    $defaultOptions = ['language' => substr(get_locale(),0, 2), 'post_author_id' => 1];

    $processCategoryGroup = ManagerFactory::processData(
        $apiResponse->getGeoCategoryGroups(),
        MAPPA_GEO_CATEGORY_GROUP,
        $defaultOptions
    );

    $processCategories = ManagerFactory::processData(
        $apiResponse->getGeoCategories(),
        MAPPA_GEO_CATEGORY,
        $defaultOptions
    );

    $processGeoPlaces = ManagerFactory::processData(
        $apiResponse->getGeoPlaces(),
        MAPPA_GEO_PLACE,
        $defaultOptions
    );

    if ($processCategories && $processCategoryGroup && $processGeoPlaces) {
        \update_option(
            'mappa_batch_updated_at',
            $apiResponse->getMetadata()['updated_at']
        );
    }

    afterrunSynchronization();
}

function prerunSynchronization()
{
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', -1);
    ignore_user_abort(true);
    set_time_limit(0);

    \wp_defer_term_counting(true);
    \wp_defer_comment_counting(true);
}

function afterrunSynchronization()
{
    \wp_defer_term_counting(false);
    \wp_defer_comment_counting(false);
}

function runManually()
{
    if (isset($_GET['process_data'])) {
        runSynchronization();
        echo 'Synchronized';
    }
}

\add_action('wp_loaded', 'Mappa\runManually');
