<?php

namespace Mappa;

require_once __DIR__ . '/../constants.php';
require_once 'media_document_manager.php';

class TermManager
{
    public static function findByTypeAndIds($taxonomyType, $ids): \WP_Term_Query
    {
        return new \WP_Term_Query([
            'taxonomy'   => $taxonomyType,
            'hide_empty' => false,
            'meta_query' => [['key' => '_mappa_id', 'value' => $ids]]
        ]);
    }

    public $mappaObject;
    public $taxonomyType;
    public $options;
    public $action;
    public $isActionSkipped;
    public $forceUpdate = false;

    public function __construct($mappaObject, $taxonomyType, $options)
    {
        $this->mappaObject  = $mappaObject;
        $this->taxonomyType = $taxonomyType;
        $this->options      = $options;
    }

    public function termParams() : ?array
    {
        throw new Exception('Implement termParams in child class.');
    }

    public function process() : \WP_Term
    {
        $termsQuery           = $this->findByData();
        $existedTerm          = $termsQuery->terms[0] ?? null;
        $isTermExisted        = !is_null($existedTerm);
        $isMappaObjectDeleted = !is_null($this->mappaObject['deleted_at']);

        # create
        if (!$isTermExisted && !$isMappaObjectDeleted) {
            return $this->createTerm();
        }

        # update
        if ($isTermExisted && !$isMappaObjectDeleted) {
            return $this->updateTerm($existedTerm);
        }

        if ($isTermExisted && $isMappaObjectDeleted) {
            return $this->destroyTerm($existedTerm);
        }

        return $existedTerm;
    }

    public function findByData() : \WP_Term_Query
    {
        return new \WP_Term_Query([
            'taxonomy'   => $this->taxonomyType,
            'hide_empty' => false,
            'meta_query' => [
                ['key' => '_mappa_id', 'value' => $this->mappaObject['id']]
            ]
        ]);
    }

    private function getTermById(int $id) : \WP_Term
    {
        return \get_term($id, $this->taxonomyType);
    }

    private function createTerm() : \WP_Term
    {
        $this->action = 'create';

        $termParams = $this->termParams();

        $term = \wp_insert_term($termParams['name'], $termParams['taxonomy']);

        if (is_a($term, 'WP_Error') && isset($term->errors['term_exists'])) {
            return $this->updateTerm(
                $this->getTermById($term->error_data['term_exists'])
            );
        }
        $term = $this->getTermById($term['term_id']);

        foreach ($termParams['meta_input'] as $key => $value) {
            \add_term_meta($term->term_id, $key, $value);
        }

        return $this->getTermById($term->term_id);
    }

    private function updateTerm(\WP_Term $term) : \WP_Term
    {
        $this->action = 'update';

        $termParams = $this->termParams();

        \wp_update_term($term->term_id, $termParams['taxonomy'], [
            'name' => $termParams['name'],
            'slug' => ''
        ]);

        foreach ($termParams['meta_input'] as $key => $value) {
            \update_term_meta($term->term_id, $key, $value);
        }

        return $this->getTermById($term->term_id);
    }

    private function destroyTerm(\WP_Term $term) : bool
    {
        $this->action = 'destroy';

        return \wp_delete_term($term->term_id, $this->taxonomyType);
    }
}
