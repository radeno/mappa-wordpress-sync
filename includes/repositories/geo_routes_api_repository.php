<?php

namespace Mappa;

require_once 'collection_api_repository.php';

class GeoRoutesApiRepository extends CollectionApiRepository
{
    public function getRequestUrl()
    {
        return MAPPA_API_URL .
            '/geo/routes.json?' .
            $this->getRequestParams();
    }
}
