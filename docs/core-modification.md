
<h1>Required osTicket Core Modification</h1>

<p>
    The Priority Notification plugin requires a small modification to
    osTicket's core code to detect when an existing ticket's priority increases.
</p>

<p>
    By default, osTicket does not provide the custom
    <code>ticket.priority.increased</code> signal used by this plugin.
    The following modification adds this functionality.
</p>

<p>
    <strong>Important:</strong> Back up your original
    <code>include/class.ticket.php</code> file before making any changes.
</p>

<h2>Step 1: Locate the Ticket Class</h2>

<p>Open the following file in your osTicket installation:</p>

<pre><code>include/class.ticket.php</code></pre>

<p>Find the <code>updateField()</code> method:</p>

<pre><code>function updateField($form, &amp;$errors) {</code></pre>

<p>Inside this method, locate the following section:</p>

<pre><code>if ($field-&gt;answer) {
    if (!$field-&gt;isEditableToStaff())
        $errors['field'] = sprintf(__('%s can not be edited'),
                __($field-&gt;getLabel()));
    elseif (!$field-&gt;save(true))
        $errors['field'] = __('Unable to update field');</code></pre>

<h2>Step 2: Replace the Existing Code</h2>

<p>Replace the code shown above with the following:</p>

<pre><code>if ($field-&gt;answer) {

    $oldPriorityId = null;
    $newPriorityId = null;

    // Only check priority changes
    if ($field-&gt;getId() == 22) {

        // Get the existing priority
        $oldPriority = $field-&gt;answer-&gt;getValue();

        if (is_object($oldPriority) &amp;&amp; isset($oldPriority-&gt;ht['priority_id'])) {
            $oldPriorityId = (int) $oldPriority-&gt;ht['priority_id'];
        }

        // Get the newly selected priority
        $newPriority = $field-&gt;getClean();

        if (is_object($newPriority) &amp;&amp; isset($newPriority-&gt;ht['priority_id'])) {
            $newPriorityId = (int) $newPriority-&gt;ht['priority_id'];
        }
    }

    // Original osTicket save logic
    if (!$field-&gt;isEditableToStaff())
        $errors['field'] = sprintf(__('%s can not be edited'),
                __($field-&gt;getLabel()));
    elseif (!$field-&gt;save(true))
        $errors['field'] = __('Unable to update field');

    // Send signal after a successful priority increase
    if (
        !$errors &amp;&amp;
        $oldPriorityId !== null &amp;&amp;
        $newPriorityId !== null &amp;&amp;
        $newPriorityId &gt; $oldPriorityId
    ) {
        Signal::send('ticket.priority.increased', $this);
    }</code></pre>

<p>
    <strong>Important:</strong> Do not remove the remaining code inside
    <code>updateField()</code>. The existing osTicket functionality
    must remain intact.
</p>

<p>
    For example, the code immediately following your replacement
    should still include:
</p>

<pre><code>// Strip tags from TextareaFields to ensure event data is not truncated
if ($field instanceof TextareaField)
    foreach ($changes as $k =&gt; $v)
        $changes[$k] = Format::truncate(Format::striptags($v), 200);</code></pre>

<h2>Step 3: Verify the Priority Field ID</h2>

<p>The modification uses:</p>

<pre><code>$field-&gt;getId() == 22</code></pre>

<p>
    The value <code>22</code> represents the priority field ID
    in the author's osTicket installation.
</p>

<p>
    <strong>Important:</strong> This ID may differ between installations.
    Verify the correct priority field ID before applying the modification.
</p>

<h2>Step 4: Verify the Signal Class</h2>

<p>The modification calls:</p>

<pre><code>Signal::send('ticket.priority.increased', $this);</code></pre>

<p>
    Ensure that osTicket's <code>Signal</code> class is available
    in the context of <code>class.ticket.php</code>.
</p>

<p>
    If necessary, load the existing signal class using the
    appropriate osTicket include mechanism.
</p>

<h2>Step 5: Test the Modification</h2>

<p>
    After saving <code>class.ticket.php</code> and enabling the plugin,
    perform the following tests:
</p>

<ol>
    <li>Open an existing ticket assigned to a team.</li>
    <li>Change its priority from Normal to High.</li>
    <li>Verify that the priority update saves successfully.</li>
    <li>
        Confirm that active members of the assigned team
        receive an email notification.
    </li>
    <li>
        Change the priority from High to Normal and verify
        that no notification is sent.
    </li>
    <li>
        Change an unrelated ticket field and verify that
        no priority notification is sent.
    </li>
</ol>

<p>
    If the plugin's <strong>High/Emergency Only</strong> option is enabled,
    notifications should only be sent when the new priority
    is High or Emergency.
</p>

<h2>Important Considerations</h2>

<ul>
    <li>
        This modification changes an osTicket core file.
    </li>
    <li>
        Back up the original file before editing.
    </li>
    <li>
        osTicket upgrades may overwrite this modification.
    </li>
    <li>
        The code only detects priority increases handled through
        <code>updateField()</code>.
    </li>
    <li>
        The comparison assumes that higher priority IDs
        represent higher urgency.
    </li>
    <li>
        Priority field IDs may differ between osTicket installations.
    </li>
    <li>
        Compatibility with other osTicket versions has not been verified.
    </li>
</ul>

<p>
    <a href="../README.md">Back to README</a>
</p>
