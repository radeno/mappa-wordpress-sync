<?php

namespace Mappa;

require_once 'api_repository.php';

class CollectionApiRepository extends ApiRepository
{
    public $results  = null;
    public $metadata = null;
    public $options  = ['updated_from' => null, 'language' => null];

    public function __construct($options = [])
    {
        parent::__construct($options);
    }

    public function callData($repository, $queryParams)
    {
        $response       = $this->getResponse($this->getRequestUrl($repository, $queryParams));
        $this->results  = $response['results'];
        $this->metadata = $response['metadata'];
    }

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function getResults()
    {
        return $this->results;
    }
}
