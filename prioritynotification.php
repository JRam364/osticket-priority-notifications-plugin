<?php


require_once(INCLUDE_DIR . 'class.plugin.php');
require_once(INCLUDE_DIR . 'class.signal.php');
require_once(INCLUDE_DIR . 'class.app.php');
require_once(INCLUDE_DIR . 'class.dispatcher.php');
require_once(INCLUDE_DIR . 'class.dynamic_forms.php');
require_once(INCLUDE_DIR . 'class.osticket.php');
require_once('config.php'); // Ensure your config file is loaded

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

}