<?php

require_once INCLUDE_DIR . 'class.plugin.php';
require_once __DIR__ . '/config.php';

class PriorityNotificationPlugin extends Plugin
{
    var $config_class = 'PriorityNotificationConfig';

    function bootstrap()
    {
        Signal::connect(
            'model.updated',
            array($this, 'onTicketUpdated')
        );
    }

    function onTicketUpdated($object, $data = null)
    {
        // Only process ticket updates
        if (!($object instanceof Ticket)) {
            return;
        }

        
    }
}