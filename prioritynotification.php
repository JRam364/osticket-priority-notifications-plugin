<?php

require_once INCLUDE_DIR . 'class.plugin.php';
require_once(INCLUDE_DIR . 'class.signal.php');
require_once __DIR__ . '/config.php';

class PriorityNotificationPlugin extends Plugin
{
    var $config_class = 'PriorityNotificationConfig';

    function bootstrap()
{
    Signal::connect(
        'ticket.priority.increased',
        array($this, 'onPriorityIncreased')
    );

    error_log("PriorityNotification: Signal listener registered");
}

function onPriorityIncreased($ticket, $data = null)
{
    error_log("PriorityNotification: Priority increase detected");
}
}