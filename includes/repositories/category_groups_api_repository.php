<?php

namespace Mappa;

require_once 'collection_api_repository.php';

class CategoryGroupsApiRepository extends CollectionApiRepository
{
    public function getRequestUrl()
    {
        return MAPPA_API_URL .
            '/category_groups.json?' .
            $this->getRequestParams();
    }
}
