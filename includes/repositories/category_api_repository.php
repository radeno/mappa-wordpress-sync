<?php

namespace Mappa;

require_once 'single_api_repository.php';

class CategoryApiRepository extends SingleApiRepository
{
    public function getRequestUrl()
    {
        return MAPPA_API_URL .
            '/categories/' .
            $this->id .
            '.json?' .
            $this->getRequestParams();
    }
}
