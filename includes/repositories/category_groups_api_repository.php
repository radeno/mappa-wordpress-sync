<?php

namespace Mappa;

require_once 'collection_api_repository.php';

class CategoryGroupsApiRepository extends CollectionApiRepository
{
    public static $repository = 'category_groups.json';

    public function call()
    {
        $response       = $this->getResponse($this->getRequestUrl($this->repository, $this->options));
        $this->results  = $response['results'];
        $this->metadata = $response['metadata'];
    }
}
