<?php

require_once INCLUDE_DIR . 'class.plugin.php';
require_once(INCLUDE_DIR . 'class.signal.php');
require_once INCLUDE_DIR . 'class.email.php';
require_once __DIR__ . '/config.php';

class PriorityNotificationPlugin extends Plugin
{
    var $config_class = 'PriorityNotificationConfig';

    // Create configuration property
    private $notificationConfig = null;

    function bootstrap()
{
    // Retrieve each instance associated with plugin
    foreach ($this->getInstances() as $instance) {

        // Retrieve configuration object associated with the particular plugin instance
        $this->notificationConfig = $instance->getConfig();
        break;
    }

    // Check whether the configuration was found
    if (!$this->notificationConfig) {
        return;
    }

    // Register signal
    Signal::connect(
        'ticket.priority.increased',
        array($this, 'onPriorityIncreased')
    );
}

function onPriorityIncreased($ticket, $data = null)
{
    global $cfg;

    //error_log('PriorityNotification: Priority increase detected');

    // Verify ticket object
    if (!($ticket instanceof Ticket)) {
        //error_log('PriorityNotification: Invalid ticket object.');
        return;
    }

    // Get configuration from the active plugin instance
    $config = $this->notificationConfig;

    if (!$config) {
        //error_log('PriorityNotification: Configuration unavailable.');
        return;
    }

    // Retrieve the new priority
    $priority = $ticket->getPriority();

    if (!$priority) {
        //error_log('PriorityNotification: Ticket has no priority.');
        return;
    }

    $priorityId = (int) $priority->getId();
    $priorityName = $priority->getDesc();

    //error_log('PriorityNotification: Priority ID = ' . $priorityId);

    // Retrieve checkbox setting
    $highEmergencyOnly = $config->get('high_emergency_only');

    // error_log(
    //     'PriorityNotification: High/Emergency Only = ' .
    //     var_export($highEmergencyOnly, true)
    // );

    // Apply restriction only when checkbox is checked
    if ($highEmergencyOnly) {
        $allowedPriorities = array(3, 4);

        if (!in_array($priorityId, $allowedPriorities, true)) {
            // error_log(
            //     'PriorityNotification: Priority not eligible for notification.'
            // );
            return;
        }
    }

    //error_log('PriorityNotification: Priority check passed.');

    // Get ticket owner
    $owner = $ticket->getOwner();
    $createdBy = $owner ? $owner->getName() : 'Unknown';

    // Get the configured alert email
    $email = $cfg->getAlertEmail();

    // Double check email exists
    if (!$email) {
        //error_log('PriorityNotification: No email account available.');
        return;
    }

    // Get team for ticket
    $team = $ticket->getTeam();

    // Get logo from images folder
    $logoPath = __DIR__ . '/images/ost-logo.png';

    $logoHtml = '';

    // Check if image exists, and is readable
    if (is_readable($logoPath)) {
        // Convert PNG to Base64
        $logoData = base64_encode(file_get_contents($logoPath));

        // Build HTML image element
        $logoHtml = '<img src="data:image/png;base64,' . $logoData . '"
                        alt="Logo"
                        width="180"
                        style="display:block; margin-bottom:20px;">';
    }

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

            // Include new priority in subject
            $subject = 'Ticket #' . $ticket->getNumber()
                    . ' - Priority Increased to ' . $priorityName;

            // Use HTML in email message
            $message = '

                <div style="font-family: Arial, sans-serif; font-size: 16px;">

                     <div style="font-family: Arial, sans-serif; font-size: 16px;">

                    ' . $logoHtml . '


                    <h2 style="font-size: 22px;">
                        Ticket Priority Increased
                    </h2>

                    <p><strong>Ticket Number:</strong> ' . htmlspecialchars($ticket->getNumber(), ENT_QUOTES, 'UTF-8') . '</p>

                    <p><strong>Subject:</strong> ' . htmlspecialchars($ticket->getSubject(), ENT_QUOTES, 'UTF-8') . '</p>

                    <p><strong>Created By:</strong> ' . htmlspecialchars($createdBy, ENT_QUOTES, 'UTF-8') . '</p>

                    <p><strong>New Priority:</strong>
                        <strong style="font-size: 18px;">' . htmlspecialchars($priorityName, ENT_QUOTES, 'UTF-8') . '</strong>
                    </p>

                    <p>
                        <a href="' . htmlspecialchars($ticketUrl, ENT_QUOTES, 'UTF-8') . '">
                            View Ticket
                        </a>
                    </p>

                    <p>Please review this ticket.</p>
                </div>
            ';

            // Tell oTicket to send message as HTML rather than plain text
            $result = $email->sendAlert(
                $recipient,
                $subject,
                $message,
                null,
                array('text' => false)
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