<?php

namespace Mappa;

require_once __DIR__ . '/../constants.php';

class ApiRepository
{
    public $options = [];

    public function __construct($options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    public function getRequestHeader()
    {
        return [
            'http' => [
                'method' => "GET",
                'header' => "Accept-language: sk\r\n" .
                    "X-APP-AUTH-TOKEN: " .
                    MAPPA_API_APP_AUTH_TOKEN,
                'timeout' => 120
            ]
        ];
    }

    public function getRequestParams()
    {
        throw new \Exception('Implement getRequestParams in child class.');
    }

    public function getRequestUrl()
    {
        throw new \Exception('Implement getRequestUrl in child class.');
    }

    public function getResponse()
    {
        $context = stream_context_create($this->getRequestHeader());

        // Open the file using the HTTP headers set above
        $result = file_get_contents($this->getRequestUrl(), false, $context);

        return json_decode($result, true);
    }
}
