<?php

namespace Mappa;

require_once __DIR__ . '/../constants.php';
require_once 'message_post_manager.php';

class MessageEventManager extends MessagePostManager
{
    public function __construct($mappaObject, $options)
    {
        return parent::__construct($mappaObject, MAPPA_MESSAGE_EVENT, $options);
    }
}
