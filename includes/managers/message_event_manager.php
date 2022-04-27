<?php

namespace Mappa;

require_once __DIR__ . '/../constants.php';
require_once 'message_post_manager.php';

class MessageEventManager extends MessagePostManager
{
    public function __construct(array $mappaObject, array $options)
    {
        return parent::__construct($mappaObject, MAPPA_MESSAGE_EVENT, $options);
    }

    public function postParams(): array
    {
        $params = parent::postParams();

        $params['meta_input']['start_date'] = $this->mappaObject['operation_times'][0]['start_date'];
        $params['meta_input']['end_date']   = $this->mappaObject['operation_times'][0]['end_date'];
        $params['meta_input']['start_time'] = $this->mappaObject['operation_times'][0]['start_time'];
        $params['meta_input']['end_time']   = $this->mappaObject['operation_times'][0]['end_time'];

        return $params;
    }
}
