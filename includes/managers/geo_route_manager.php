<?php

namespace Mappa;

require_once __DIR__ . '/../constants.php';
require_once 'geo_spatial_manager.php';

class GeoRouteManager extends GeoSpatialManager
{
    public function __construct(array $mappaObject, array $options)
    {
        return parent::__construct($mappaObject, MAPPA_GEO_ROUTE, $options);
    }
}
