<?php

namespace Mappa;

class WpMappaHelper
{
    public static function getPlaceById(string $mappaId, array $options): array
    {
        $repo = new GeoPlacesApiRepository($options);
        return $repo->getById($mappaId);
    }

    public static function getRouteById(string $mappaId, array $options): array
    {
        $repo = new GeoRoutesApiRepository($options);
        return $repo->getById($mappaId);
    }

    public static function getEventById(string $mappaId, array $options): array
    {
        $repo = new MessageEventsApiRepository($options);
        return $repo->getById($mappaId);
    }

    public static function getCategoryById(string $mappaId, array $options): array
    {
        $repo = new CategoriesApiRepository($options);
        return $repo->getById($mappaId);
    }

    public static function getCategoryGroupById(string $mappaId, array $options): array
    {
        $repo = new CategoryGroupsApiRepository($options);
        return $repo->getById($mappaId);
    }

    public static function updatePlace(array $mappaObject, array $options): GeoPlaceManager
    {
        return ManagerFactory::processSingleData($mappaObject, MAPPA_GEO_PLACE, $options);
    }

    public static function updateRoute(array $mappaObject, array $options): GeoRouteManager
    {
        return ManagerFactory::processSingleData($mappaObject, MAPPA_GEO_ROUTE, $options);
    }

    public static function updateCategory(array $mappaObject, array $options): GeoCategoryManager
    {
        return ManagerFactory::processSingleData($mappaObject, MAPPA_GEO_CATEGORY, $options);
    }

    public static function updateCategoryGroup(array $mappaObject, array $options): GeoCategoryGroupManager
    {
        return ManagerFactory::processSingleData($mappaObject, MAPPA_GEO_CATEGORY_GROUP, $options);
    }

    public static function updateEvent(array $mappaObject, array $options): MessageEventManager
    {
        return ManagerFactory::processSingleData($mappaObject, MAPPA_MESSAGE_EVENT, $options);
    }

    public static function fetchAndUpdatePlace(int $postId, array $options): ?GeoPlaceManager
    {
        $mappaId = \get_post_meta($postId, '_mappa_id', true);

        if (empty($mappaId)) return null;

        $mappaObject = self::getPlaceById($mappaId, [
            'language' => $options['language']
        ]);
        return self::updatePlace($mappaObject, [
            'language' => $options['language'],
            'force_update' => $options['force_update'] ?? false,
            'force_object_id' => $options['force_object_id'] ?: null
        ]);
    }

    public static function fetchAndUpdateRoute(int $postId, array $options): ?GeoRouteManager
    {
        $mappaId = \get_post_meta($postId, '_mappa_id', true);

        if (empty($mappaId)) return null;

        $mappaObject = self::getRouteById($mappaId, [
            'language' => $options['language']
        ]);
        return self::updateRoute($mappaObject, [
            'language' => $options['language'],
            'force_update' => $options['force_update'] ?? false,
            'force_object_id' => $options['force_object_id'] ?: null
        ]);
    }

    public static function fetchAndUpdateEvent(int $postId, array $options): ?MessageEventManager
    {
        $mappaId = \get_post_meta($postId, '_mappa_id', true);

        if (empty($mappaId)) return null;

        $mappaObject = self::getEventById($mappaId, [
            'language' => $options['language']
        ]);
        return self::updateEvent($mappaObject, [
            'language' => $options['language'],
            'force_update' => $options['force_update'] ?? false,
            'force_object_id' => $options['force_object_id'] ?: null
        ]);
    }

    public static function fetchAndUpdateCategory(int $termId, array $options): ?GeoCategoryManager
    {
        $mappaId = \get_term_meta($termId, '_mappa_id', true);

        if (empty($mappaId)) return null;

        $mappaObject = self::getCategoryById($mappaId, [
            'language' => $options['language']
        ]);
        return self::updateCategory($mappaObject, [
            'language' => $options['language'],
            'force_update' => $options['force_update'] ?? false,
            'force_object_id' => $options['force_object_id'] ?: null
        ]);
    }

    public static function fetchAndUpdateCategoryGroup(int $termId, array $options): ?GeoCategoryGroupManager
    {
        $mappaId = \get_term_meta($termId, '_mappa_id', true);

        if (empty($mappaId)) return null;

        $mappaObject = self::getCategoryGroupById($mappaId, [
            'language' => $options['language']
        ]);
        return self::updateCategoryGroup($mappaObject, [
            'language' => $options['language'],
            'force_update' => $options['force_update'] ?? false,
            'force_object_id' => $options['force_object_id'] ?: null
        ]);
    }
}
