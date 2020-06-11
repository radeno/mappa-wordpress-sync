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
        $this->options = array_merge($this->options, $options);
    }

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function getResults()
    {
        return $this->results;
    }

    public function getRequestParams()
    {
        return http_build_query([
            'updated_from' => $this->options['updated_from'],
        ]);
    }
}
