<?php

namespace Mappa;

require_once __DIR__ . '/../constants.php';

class ApiRepository
{
    public $options = [];

    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    public function getRequestHeaders() : array
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

    public function getRequestParams(array $params) : string
    {
        return http_build_query(array_merge($this->options, $params));
    }

    public function getRequestUrl(string $path, ?array $params = null) : string
    {
        return MAPPA_API_URL . '/' . $path . (!is_null($params) ? '?' . $this->getRequestParams($params) : '');
    }

    public function getResponse(string $queryPath) : array
    {
        $context = stream_context_create($this->getRequestHeaders());

        // Open the file using the HTTP headers set above
        $result = file_get_contents($queryPath, false, $context);

        return json_decode($result, true);
    }

    public function getBinaryResponse(string $queryPath)
    {
        $context = stream_context_create($this->getRequestHeaders());

        // Open the file using the HTTP headers set above
        $result = file_get_contents($queryPath, false, $context);

        return $result;
    }
}
