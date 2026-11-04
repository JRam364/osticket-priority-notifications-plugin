
<h2>Priority Notification Plugin</h2>

<p>
    By default, osTicket does not automatically notify assigned team members when an existing ticket's priority increases. This can result in urgent tickets going unnoticed or being addressed later than necessary.

The Priority Notification Plugin solves this problem by automatically sending email notifications to active members of the ticket's assigned team whenever its priority increases. Administrators can also configure the plugin to send notifications only when a ticket is escalated to High or Emergency priority.
</p>

<h3>Features</h3>
<ul>
    <li>Automatically sends notifications when ticket priority increases.</li>
    <li>Optional setting to notify only for High and Emergency priorities.</li>
    <li>Emails all active members of the ticket's assigned team.</li>
    <li>Includes the ticket number, subject, requester, new priority, and ticket link.</li>
    <li>Uses formatted HTML email notifications.</li>
</ul>
<p>Shown below is an example of an email that is sent to team members when a priority is increased:</p>
<img width="402" height="375" alt="image" src="https://github.com/user-attachments/assets/aad1e4c5-da78-49c9-9859-76775f1fa741" />

<h3>Configuration</h3>
<p>
    <strong>High and Emergency Only:</strong>
    When enabled, notifications are sent only when the new
    priority is High or Emergency. When disabled, notifications
    are sent for all priority increases.
</p>
<img width="1207" height="194" alt="image" src="https://github.com/user-attachments/assets/9739f9d9-be54-4e8d-a59c-5d57c46392e7" />

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

<br>


<h2>Installation and Setup</h2>

<h3>1. Download the Plugin</h3>

<p>
    Download the latest release from this repository or clone the repository.
</p>

<p>
    Copy the plugin files into the following directory of your osTicket installation:
</p>

<pre><code>include/plugins/priority-notification/</code></pre>

<h3>2. Apply the Required Core Modification</h3>

<p>
    This plugin uses a custom signal called
    <code>ticket.priority.increased</code> to detect when an existing
    ticket's priority increases.
</p>

<p>
    Since this signal is not included in standard osTicket installations,
    a small modification to <code>include/class.ticket.php</code> is required.
</p>

<p>
    Follow the
    <a href="docs/core-modification.md">Core Modification Instructions</a>
    to make the necessary changes.
</p>

<p>
    <strong>Important:</strong> The priority field ID used in the modification
    must match the priority field ID in your osTicket installation.
    The example uses field ID <code>22</code>.
</p>

<h3>3. Install and Enable the Plugin</h3>

<ol>
    <li>Sign in to osTicket as an administrator.</li>
    <li>Navigate to <strong>Admin Panel &gt; Manage &gt; Plugins</strong>.</li>
    <li>Click <strong>Add New Plugin</strong>.</li>
    <li>Find <strong>Priority Notification</strong> and install it.</li>
    <li>Open the installed plugin and add a new instance.</li>
    <li>
        Give the instance a name, such as
        <code>Priority Notification - Production</code>.
    </li>
    <li>Configure the <strong>High/Emergency Only</strong> setting.</li>
    <li>Save the configuration and enable the plugin instance.</li>
</ol>

<h3>4. Configure Notification Settings</h3>

<p>
    The plugin provides a <strong>High/Emergency Only</strong> checkbox
    to control which priority increases trigger email notifications.
</p>

<ul>
    <li>
        <strong>Enabled:</strong> Notifications are sent only when
        the new priority is High or Emergency.
    </li>
    <li>
        <strong>Disabled:</strong> Notifications are sent whenever
        an existing ticket's priority increases.
    </li>
</ul>

<p>
    Notifications are sent to active members of the ticket's assigned team.
    Tickets without an assigned team will not generate team-member notifications.
</p>

<h3>5. Verify Email Configuration</h3>

<p>
    Ensure that outgoing email is configured and functioning in osTicket
    before testing the plugin.
</p>

<p>
    The plugin uses osTicket's configured alert email to send HTML
    notifications containing:
</p>

<ul>
    <li>Ticket number</li>
    <li>Ticket subject</li>
    <li>Ticket creator</li>
    <li>New priority level</li>
    <li>A direct link to view the ticket</li>
</ul>

<h3>6. Test the Plugin</h3>

<ol>
    <li>Open an existing ticket with an assigned team.</li>
    <li>Ensure the ticket currently has Normal priority.</li>
    <li>Change the ticket's priority from Normal to High.</li>
    <li>Save the change.</li>
    <li>Verify that active members of the assigned team receive the notification email.</li>
    <li>Change the priority from High back to Normal.</li>
    <li>Verify that no notification is sent for the priority decrease.</li>
    <li>
        If the High/Emergency Only setting is enabled, verify that
        notifications are sent only when the new priority is High or Emergency.
    </li>
</ol>

<h3>Important Notes</h3>

<ul>
    <li>
        Back up <code>include/class.ticket.php</code> before modifying it.
    </li>
    <li>
        osTicket upgrades may overwrite the required core modification,
        requiring it to be reapplied.
    </li>
    <li>
        The plugin detects priority increases handled by the modified
        <code>updateField()</code> method.
    </li>
    <li>
        The plugin does not send notifications when tickets are initially created.
    </li>
    <li>
        Priority changes made through other update mechanisms may not
        trigger the custom signal.
    </li>
    <li>
        Verify the priority field ID and priority values for your installation.
    </li>
    <li>
        Compatibility with other osTicket versions is not guaranteed.
    </li>
</ul>
