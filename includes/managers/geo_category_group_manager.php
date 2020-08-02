<?php

namespace Mappa;

require_once __DIR__ . '/../constants.php';
require_once 'geo_term_manager.php';

class GeoCategoryGroupManager extends GeoTermManager
{
    public static function findByIds(array $ids): \WP_Term_Query
    {
        return parent::findByTypeAndIds(MAPPA_GEO_CATEGORY_GROUP, $ids);
    }

    public function __construct(array $mappaObject, array $options)
    {
        return parent::__construct($mappaObject, MAPPA_GEO_CATEGORY_GROUP, $options);
    }
}
