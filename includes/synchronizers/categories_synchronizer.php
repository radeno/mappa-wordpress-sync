<?php

namespace Mappa;

require_once __DIR__ . '/../helpers/synchronizer_helper.php';
require_once __DIR__ . '/../managers/manager_factory.php';
require_once __DIR__ . '/../repositories/categories_api_repository.php';

class CategoriesSynchronizer {
    public static function call($options)
    {
        SynchronizerHelper::preRunSynchronization();

        $apiResponse = new CategoriesApiRepository();
        $apiResponse->call();

        $processedCategories = ManagerFactory::processData(
            $apiResponse->getResults(),
            MAPPA_GEO_CATEGORY,
            $options
        );

        SynchronizerHelper::afterRunSynchronization();

        return $processedCategories;
    }
}
