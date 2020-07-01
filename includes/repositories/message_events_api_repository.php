<?php

namespace Mappa;

require_once 'collection_api_repository.php';

class MessageEventsApiRepository extends CollectionApiRepository
{
    public function call()
    {
        $response       = $this->getResponse($this->getRequestUrl('message/events.json', $this->options));
        $this->results  = $response['results'];
        $this->metadata = $response['metadata'];
    }

    public function searchTitle($title)
    {
        $response       = $this->getResponse($this->getRequestUrl('message/events/search.json', ['q_title' => $title]));
        $this->results  = $response['results'];
        $this->metadata = $response['metadata'];
    }

    public function getById($id)
    {
        return $this->getResponse($this->getRequestUrl("message/events/{$id}.json", $this->options));
    }
}
