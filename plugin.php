<?php

return array(
    'id' => 'josh:prioritynotification',
    'version' => '1.0',
    'name' => 'Priority Notification',
    'author' => 'Josh',
    'description' => 'Notify agents when ticket priority increases.',
    'plugin' => 'prioritynotification.php:PriorityNotificationPlugin',
    'config' => 'config.php:PriorityNotificationConfig',
);

