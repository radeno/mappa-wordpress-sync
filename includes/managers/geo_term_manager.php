<?php

namespace Mappa;

require_once __DIR__ . '/../constants.php';
require_once 'term_manager.php';

class GeoTermManager extends TermManager
{
    public function termParams() : array
    {
        $attrs = [
            'name'     => $this->mappaObject['name_translations'][$this->options['language']],
            'taxonomy' => $this->taxonomyType
        ];

        $metaAttrs = [
            '_mappa_id'         => $this->mappaObject['id'],
            '_mappa_updated_at' => $this->mappaObject['updated_at']
        ];

        if (!is_null($this->mappaObject['image'])) {
            $imageManager = new MediaDocumentManager($this->mappaObject['image']);
            $image        = $imageManager->process();
            if (isset($image->ID)) {
                $metaAttrs['_thumbnail_id'] = $image->ID;
            }
        }

        $attrs['meta_input'] = $metaAttrs;

        return $attrs;
    }
}
