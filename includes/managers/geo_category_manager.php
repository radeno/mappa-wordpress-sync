<?php

namespace Mappa;

require_once __DIR__ . '/../constants.php';
require_once 'geo_term_manager.php';

class GeoCategoryManager extends GeoTermManager
{
    public static function findByIds(array $ids, string $language): \WP_Term_Query
    {
        return parent::findByTypeAndIds(MAPPA_GEO_CATEGORY, $ids, $language);
    }

    public function __construct(array $mappaObject, array $options)
    {
        return parent::__construct($mappaObject, MAPPA_GEO_CATEGORY, $options);
    }

    public function termParams() : array
    {
        $attrs = parent::termParams();

        $attrs['meta_input']['_mappa_category_group_ids'] = $this->mappaObject['category_group_ids'];

        return $attrs;
    }
}
