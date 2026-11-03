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

    $config = $this->getConfig();
   


    $priority = $ticket->getPriority();
    
    // Check for priority
    if (!$priority) {
    error_log('PriorityNotification: No priority found.');
    return;
    }

    // Get priority name
    $priorityId = (int) $priority->getId();
    $priorityName = $priority ? $priority->getDesc() : 'Unknown';

    // Get owner of the ticket
    $owner = $ticket->getOwner();
    $createdBy = $owner ? $owner->getName() : 'Unknown';

    // Show the priority number from database
    error_log('PriorityNotification: Priority ID = ' . $priorityId);

    // Check if new priority applies
    if ($config->get('high_emergency_only')) {
        $allowedPriorities = array(3, 4);

        if (!in_array($priorityId, $allowedPriorities, true)) {
            error_log('PriorityNotification: Priority not eligible for notification.');
            return;
        }
    }

    // Show that new priority will allow email
    error_log('PriorityNotification: Priority check passed.');

    // Get the configured alert email
    $email = $cfg->getAlertEmail();

    // Double check email exists
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

            // Build a direct link to the ticket
            $ticketUrl = rtrim($cfg->getUrl(), '/')
                    . '/scp/tickets.php?id='
                    . $ticket->getId();

            $subject = 'Ticket #' . $ticket->getNumber()
                    . ' - Priority Increased to ' . $priorityName;

            $message = "A ticket's priority has been increased.\n\n"
                    . "Ticket Number: " . $ticket->getNumber() . "\n"
                    . "Subject: " . $ticket->getSubject() . "\n"
                    . "Created By: " . $createdBy . "\n"
                    . "New Priority: " . $priorityName . "\n\n"
                    . "View Ticket: " . $ticketUrl . "\n\n"
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