<?php

namespace Mappa;

require_once 'collection_api_repository.php';

class GeoRoutesApiRepository extends CollectionApiRepository
{
    public function call()
    {
        $response       = $this->getResponse($this->getRequestUrl('geo/routes.json', $this->options));
        $this->results  = $response['results'];
        $this->metadata = $response['metadata'];
    }

    public function searchTitle($title)
    {
        $response       = $this->getResponse($this->getRequestUrl('geo/routes/search.json', ['q_title' => $title]));
        $this->results  = $response['results'];
        $this->metadata = $response['metadata'];
    }

    public function getById($id)
    {
        return $this->getResponse($this->getRequestUrl("geo/routes/{$id}.json", $this->options));
    }
}
