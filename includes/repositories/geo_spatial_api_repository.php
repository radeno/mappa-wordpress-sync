<?php

namespace Mappa;

require_once 'single_api_repository.php';

class GeoSpatialApiRepository extends SingleApiRepository
{
    public function getRequestUrl()
    {
        return MAPPA_API_URL .
            '/geo/spatials/' .
            $this->id .
            '.json?' .
            $this->getRequestParams();
    }
}
