<?php

namespace Mappa;

require_once 'collection_api_repository.php';

class CategoryGroupsApiRepository extends CollectionApiRepository
{
    public function call()
    {
        $response       = $this->getResponse($this->getRequestUrl('category_groups.json', $this->options));
        $this->results  = $response['results'];
        $this->metadata = $response['metadata'];
    }
}
