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

function onPriorityIncreased($ticket, $data)
{
    error_log("PriorityNotification: Priority increase detected");
    // If there is no ticket, exit
    if (!$ticket)
            return;
    // Check team members that could get the alert
    $team = $ticket->getTeam();
    if ($team) {
            error_log('Assigned team: ' . $team->getName());

            foreach ($team->getMembers() as $staff) {
                if ($staff) {
                    error_log(
                        'Team member: '
                        . $staff->getName()
                        . ' <'
                        . $staff->getEmail()
                        . '>'
                    );
                }
            }
        }
}
}