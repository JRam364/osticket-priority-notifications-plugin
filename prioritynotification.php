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

    // Get the configured alert email
    $email = $cfg->getAlertEmail();

    if (!$email) {
        error_log('PriorityNotification: No email account available.');
        return;
    }

    // Get team for ticket
    $team = $ticket->getTeam();

    // Loop through team members
    if ($team) {
        foreach ($team->getMembers() as $staff) {

            // Make sure they are active
            if (!$staff || !$staff->isActive()) {
                continue;
            }

            // Get their email
            $recipient = $staff->getEmail();

            if (!$recipient) {
                continue;
            }

            // Build subject and message of email
            $subject = 'Ticket #' . $ticket->getNumber()
                    . ' - Priority Increased';

            $message = "A ticket's priority has been increased.\n\n"
                    . "Ticket Number: " . $ticket->getNumber() . "\n"
                    . "Subject: " . $ticket->getSubject() . "\n\n"
                    . "Please review this ticket.";

            $result = $email->sendAlert(
                $recipient,
                $subject,
                $message,
                null,
                array('text' => true)
            );

            if ($result) {
                error_log('PriorityNotification: Email sent to ' . $recipient);
            } else {
                error_log('PriorityNotification: Failed to send to ' . $recipient);
            }
        }
    }
    


}
}