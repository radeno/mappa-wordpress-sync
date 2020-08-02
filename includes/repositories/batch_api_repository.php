<?php

namespace Mappa;

require_once 'collection_api_repository.php';

class BatchApiRepository extends CollectionApiRepository
{
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->call('batches.json', $options);
    }

    public function call(string $repository, array $queryParams) : void
    {
        $response       = $this->getResponse($this->getRequestUrl($repository, $queryParams));
        $this->results  = $response['results'];
        $this->metadata = $response['metadata'];
    }

    public function getGeoCategoryGroups() : array
    {
        return $this->results['category_groups']['results'];
    }

    public function getGeoCategories() : array
    {
        return $this->results['categories']['results'];
    }

    public function getGeoPlaces() : array
    {
        return $this->results['geo_places']['results'];
    }

    public function getGeoRoutes() : array
    {
        return $this->results['geo_routes']['results'];
    }

    public function getMessageNews() : array
    {
        return $this->results['message_news']['results'];
    }

    public function getMessageEvents() : array
    {
        return $this->results['message_events']['results'];
    }
}
