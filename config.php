<?php
class PriorityNotificationConfig extends PluginConfig
{   
    
    function getOptions()
    {
        // Add option for only notifying of high priority increases
        return array(
            'high_emergency_only' => new BooleanField(array(
                'label' => 'High and Emergency Only',
                'default' => false,
                'configuration' => array(
                    'desc' => 'Only send emails when priority increases to High or Emergency.'
                )
            ))
        );
    }
}