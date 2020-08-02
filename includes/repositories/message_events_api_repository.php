<?php

namespace Mappa;

require_once 'collection_api_repository.php';

class MessageEventsApiRepository extends CollectionApiRepository
{
    public function call() : void
    {
        $response       = $this->getResponse($this->getRequestUrl('message/events.json', $this->options));
        $this->results  = $response['results'];
        $this->metadata = $response['metadata'];
    }

    public function searchTitle(string $title) : void
    {
        $response       = $this->getResponse($this->getRequestUrl('message/events/search.json', ['q_title' => $title]));
        $this->results  = $response['results'];
        $this->metadata = $response['metadata'];
    }

    public function getById(string $id) : array
    {
        return $this->getResponse($this->getRequestUrl("message/events/{$id}.json", $this->options));
    }
}
