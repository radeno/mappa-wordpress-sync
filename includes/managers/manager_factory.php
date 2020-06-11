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
    public static function processData(array $dataArray, string $type, array $options) : bool
    {
        $processed    = 0;
        $nonProcessed = 0;
        $breakAfter   = isset($options['break_after'])
            ? $options['break_after']
            : false;

        foreach ($dataArray as $dataObject) {
            $manager = self::newManager($dataObject, $type, ['language' => $options['language']]);
            $manager->process();

            if (!$manager->isActionSkipped) {
                $processed += 1;
            } else {
                $nonProcessed += 1;
            }

            if ($breakAfter && $processed >= $breakAfter) {
                break;
                return false;
            }
        }

        if ($processed + $nonProcessed != count($dataArray)) {
            return false;
        }

        return true;
    }

    public static function newManager($mappaObject, $type, $options)
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

            case MAPPA_MESSAGE_EVENT:
                return new MessageEventManager($mappaObject, $options);
                break;
        }
    }
}
