<?php

namespace Mappa;

require_once 'api_repository.php';

class CollectionApiRepository extends ApiRepository
{
    public $results  = null;
    public $metadata = null;
    public $options  = ['updated_from' => null, 'language' => null];

    public function __construct(array $options = [])
    {
        parent::__construct($options);
    }

    public function callData(string $repository, array $queryParams) : void
    {
        $response       = $this->getResponse($this->getRequestUrl($repository, $queryParams));
        $this->results  = $response['results'];
        $this->metadata = $response['metadata'];
    }

    public function getMetadata() : array
    {
        return $this->metadata;
    }

    public function getResults() : array
    {
        return $this->results;
    }
}
