
<h2>Priority Notification Plugin</h2>

<p>
    Sends email notifications to assigned team members
    whenever a ticket's priority is increased.
</p>

<h3>Features</h3>
<ul>
    <li>Automatically sends notifications when ticket priority increases.</li>
    <li>Optional setting to notify only for High and Emergency priorities.</li>
    <li>Emails all active members of the ticket's assigned team.</li>
    <li>Includes the ticket number, subject, requester, new priority, and ticket link.</li>
    <li>Uses formatted HTML email notifications.</li>
</ul>

<h3>Configuration</h3>
<p>
    <strong>High and Emergency Only:</strong>
    When enabled, notifications are sent only when the new
    priority is High or Emergency. When disabled, notifications
    are sent for all priority increases.
</p>

<h3>Requirements</h3>
<ul>
    <li>osTicket 1.18.x</li>
    <li>Configured outgoing email</li>
    <li>Custom <code>ticket.priority.increased</code> signal</li>
</ul>

<h3>Important Notes</h3>
<ul>
    <li>Tickets must have an assigned team for notifications to be sent.</li>
    <li>Priority IDs 3 and 4 are treated as High and Emergency.</li>
    <li>The custom priority-increase signal must be maintained during osTicket updates.</li>
</ul>
