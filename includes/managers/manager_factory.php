<?php

namespace Mappa;

require_once __DIR__ . '/../constants.php';
require_once 'geo_category_manager.php';
require_once 'geo_category_group_manager.php';
require_once 'geo_place_manager.php';
require_once 'geo_route_manager.php';
require_once 'message_event_manager.php';
require_once 'media_document_manager.php';

class ManagerFactory
{
    public static function processData(array $dataArray, string $type, array $options) : array
    {
        $processed    = 0;
        $nonProcessed = 0;

        $processedData = array_map(function($dataObject) use($type, $options, $processed, $nonProcessed) {
            $manager = self::newManager($dataObject, $type, $options);
            $manager->process();

            if (!$manager->isActionSkipped) {
                $processed += 1;
            } else {
                $nonProcessed += 1;
            }
        }, $dataArray);

        return $processedData;
    }

    public static function processSingleData(array $dataObject, string $type, array $options) : object
    {
        $manager = self::newManager($dataObject, $type, $options);
        $manager->process();

        return $manager;
    }

    public static function newManager(array $mappaObject, string $type, array $options)
    {
        switch ($type) {
            case MAPPA_GEO_CATEGORY:
                return new GeoCategoryManager($mappaObject, $options);
                break;

            case MAPPA_GEO_CATEGORY_GROUP:
                return new GeoCategoryGroupManager($mappaObject, $options);
                break;

            case MAPPA_GEO_PLACE:
                return new GeoPlaceManager($mappaObject, $options);
                break;

            case MAPPA_GEO_ROUTE:
                return new GeoRouteManager($mappaObject, $options);
                break;

            case MAPPA_MESSAGE_EVENT:
                return new MessageEventManager($mappaObject, $options);
                break;
        }
    }
}
