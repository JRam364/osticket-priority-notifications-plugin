<?php

return array(
    'id' => 'josh:prioritynotification',
    'version' => '0.1',
    'name' => 'Priority Notification',
    'author' => 'Josh',
    'description' => 'Notify agents when ticket priority increases.',
    'plugin' => 'prioritynotification.php:PriorityNotificationPlugin',
    'config' => 'config.php:PriorityNotificationConfig',
);

