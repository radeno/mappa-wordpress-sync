<?php

namespace Mappa;

require_once 'collection_api_repository.php';

class GeoRoutesApiRepository extends CollectionApiRepository
{
    public function call() : void
    {
        $response       = $this->getResponse($this->getRequestUrl('geo/routes.json', $this->options));
        $this->results  = $response['results'];
        $this->metadata = $response['metadata'];
    }

    public function searchTitle(string $title) : void
    {
        $response       = $this->getResponse($this->getRequestUrl('geo/routes/search.json', ['q_title' => $title]));
        $this->results  = $response['results'];
        $this->metadata = $response['metadata'];
    }

    public function getById(string $id) : array
    {
        return $this->getResponse($this->getRequestUrl("geo/routes/{$id}.json", $this->options));
    }

    public function getMapImageById(string $id)
    {
        return $this->getBinaryResponse($this->getRequestUrl("geo/routes_extras/{$id}/map_image.png", $this->options));
    }
}
