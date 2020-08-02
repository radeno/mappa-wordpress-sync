<?php

namespace Mappa;

require_once __DIR__ . '/../helpers/synchronizer_helper.php';
require_once __DIR__ . '/../managers/manager_factory.php';
require_once __DIR__ . '/../repositories/category_groups_api_repository.php';

class CategoryGroupsSynchronizer {
    public static function call(array $options) : array
    {
        SynchronizerHelper::preRunSynchronization();

        $apiResponse = new CategoryGroupsApiRepository();
        $apiResponse->call();

        $processedCategoryGroups = ManagerFactory::processData(
            $apiResponse->getResults(),
            MAPPA_GEO_CATEGORY_GROUP,
            $options
        );

        SynchronizerHelper::afterRunSynchronization();

        return $processedCategoryGroups;
    }
}
