<?php

namespace Mappa;

require_once 'api_repository.php';

class SingleApiRepository extends ApiRepository
{
    public $id      = null;
    public $options = ['language' => null];

    public static function getById(string $objectId)
    {
        $self = new self($objectId);
        return $self->get();
    }

    public function __construct($id, $options = [])
    {
        $this->id = $id;
        parent::__construct($options);
    }

    public function getRequestParams()
    {
        return http_build_query([
            'lang' => $this->options['language']
        ]);
    }

    public function get()
    {
        return $this->getResponse();
    }
}
