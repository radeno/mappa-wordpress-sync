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

    public function getRequestHeaders()
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

    public function getRequestParams($params)
    {
        return http_build_query(array_merge($this->options, $params));
    }

    public function getRequestUrl($path, $params = null)
    {
        return MAPPA_API_URL . '/' . $path . (!is_null($params) ? '?' . $this->getRequestParams($params) : '');
    }

    public function getResponse($queryPath)
    {
        $context = stream_context_create($this->getRequestHeaders());

        // Open the file using the HTTP headers set above
        $result = file_get_contents($queryPath, false, $context);

        return json_decode($result, true);
    }
}
