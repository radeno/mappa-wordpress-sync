<?php

namespace Mappa;

require_once 'collection_api_repository.php';

class CategoriesApiRepository extends CollectionApiRepository
{
    public function getRequestUrl()
    {
        return MAPPA_API_URL .
            '/categories.json?' .
            $this->getRequestParams();
    }
}
