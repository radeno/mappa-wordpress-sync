<?php

namespace Mappa;

require_once __DIR__ . '/../constants.php';
require_once 'geo_term_manager.php';

class GeoCategoryManager extends GeoTermManager
{
    public static function findByIds(array $ids): \WP_Term_Query
    {
        return parent::findByTypeAndIds(MAPPA_GEO_CATEGORY, $ids);
    }

    public function __construct($mappaObject, $options)
    {
        return parent::__construct($mappaObject, MAPPA_GEO_CATEGORY, $options);
    }
}
