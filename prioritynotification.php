<?php

require_once INCLUDE_DIR . 'class.plugin.php';
require_once(INCLUDE_DIR . 'class.signal.php');
require_once INCLUDE_DIR . 'class.email.php';
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
    global $cfg;

    error_log("PriorityNotification: Priority increase detected");

    // Verify that this is a valid ticket object
    if (!($ticket instanceof Ticket)) {
        error_log("PriorityNotification: Invalid ticket object.");
        return;
    }

    // Check team members that could get the alert
    // $team = $ticket->getTeam();

    // if ($team) {
    //         error_log('Assigned team: ' . $team->getName());

    //         foreach ($team->getMembers() as $staff) {
    //             if ($staff) {
    //                 error_log(
    //                     'Team member: '
    //                     . $staff->getName()
    //                     . ' <'
    //                     . $staff->getEmail()
    //                     . '>'
    //                 );
    //             }
    //         }
    //     }
    // else {
    //     error_log('PriorityNotification: No assigned team.');
    //     }

     

    // Get the configured alert email
    $email = $cfg->getAlertEmail();

    if (!$email) {
        error_log('PriorityNotification: No email account available.');
        return;
    }

    // Hardcode email address as the only recepient
    $recipient = 'testemail';

    $subject = '[DEV TEST] Ticket #' .
        $ticket->getNumber() .
        ' - Priority Increased';

    $message = "A ticket's priority has been increased.\n\n"
        . "Ticket Number: " . $ticket->getNumber() . "\n"
        . "Subject: " . $ticket->getSubject() . "\n\n"
        . "This is a test notification from development.";

    // Send exactly one email per callback execution
    $result = $email->sendAlert(
        $recipient,
        $subject,
        $message,
        null,
        array('text' => true)
    );

    error_log(
        $result
            ? 'PriorityNotification: Test email submitted.'
            : 'PriorityNotification: Test email failed.'
    );

}
}