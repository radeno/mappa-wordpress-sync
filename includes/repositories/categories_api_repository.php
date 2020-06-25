<?php

namespace Mappa;

require_once 'collection_api_repository.php';

class CategoriesApiRepository extends CollectionApiRepository
{
    public static $repository = 'categories.json';

    public function call()
    {
        $response       = $this->getResponse($this->getRequestUrl($this->repository, $this->options));
        $this->results  = $response['results'];
        $this->metadata = $response['metadata'];
    }
}
