<?php
class PriorityNotificationConfig extends PluginConfig {

    function getOptions() {
       
        return array(
            
                
            // Add option for only notifying of high priority increases
            'high_emergency_only' => new BooleanField(array(
                    'label' => 'High and Emergency Only',
                    'default' => true,
                    'configuration' => array(
                        'desc' => 'Only send notifications when the new priority is High or Emergency.'
                    )
                ))
            );
        }
}

