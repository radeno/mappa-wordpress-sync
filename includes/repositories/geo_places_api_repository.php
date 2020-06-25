<?php

namespace Mappa;

require_once 'collection_api_repository.php';

class GeoPlacesApiRepository extends CollectionApiRepository
{
    public function getRequestUrl()
    {
        return MAPPA_API_URL .
            '/geo/places.json?' .
            $this->getRequestParams();
    }
}
