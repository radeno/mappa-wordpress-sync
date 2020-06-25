<?php

namespace Mappa;

require_once 'collection_api_repository.php';

class BatchApiRepository extends CollectionApiRepository
{
    public function __construct($options = [])
    {
        parent::__construct($options);

        $this->call('batches.json', $options);
    }

    public function call($repository, $queryParams)
    {
        $response       = $this->getResponse($this->getRequestUrl($repository, $queryParams));
        $this->results  = $response['results'];
        $this->metadata = $response['metadata'];
    }

    public function getGeoCategoryGroups()
    {
        return $this->results['category_groups']['results'];
    }

    public function getGeoCategories()
    {
        return $this->results['categories']['results'];
    }

    public function getGeoPlaces()
    {
        return $this->results['geo_places']['results'];
    }

    public function getGeoRoutes()
    {
        return $this->results['geo_routes']['results'];
    }

    public function getMessageNews()
    {
        return $this->results['message_news']['results'];
    }

    public function getMessageEvents()
    {
        return $this->results['message_events']['results'];
    }
}
