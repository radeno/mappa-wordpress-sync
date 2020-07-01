<?php

namespace Mappa;

require_once 'collection_api_repository.php';

class CategoriesApiRepository extends CollectionApiRepository
{
    public function call()
    {
        $response       = $this->getResponse($this->getRequestUrl('categories.json', $this->options));
        $this->results  = $response['results'];
        $this->metadata = $response['metadata'];
    }

    public function getById($id)
    {
        return $this->getResponse($this->getRequestUrl("categories/{$id}.json", $this->options));
    }
}
