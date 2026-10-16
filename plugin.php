<?php
//only include following code if you have files in an include folder associeted with this plugin
set_include_path(get_include_path().PATH_SEPARATOR.dirname(__file__).'/include');
return array(
 'id' => 'josh:prioritynotification',
 'version' => '0.1',
 'name' => 'Priority Notification',
 'author' => 'Josh',
 'description' => 'Tickets will notify users when priority changes',
 //change below once you have a php file for the plugin logic
 'plugin' => 'prioritynotification.php:PriorityNotification',
 'config' => 'config.php:PriorityNotificationConfig',

);

