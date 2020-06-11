<?php

namespace Mappa;

require_once 'single_api_repository.php';

class MessagePostApiRepository extends SingleApiRepository
{
    public function getRequestUrl()
    {
        return MAPPA_API_URL .
            '/message/posts/' .
            $this->id .
            '.json?' .
            $this->getRequestParams();
    }
}
