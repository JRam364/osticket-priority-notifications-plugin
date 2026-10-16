<?php
class PriorityNotificationConfig extends PluginConfig {

    function getOptions() {
       function getOptions()
    {
        return array(
            'enabled' => new BooleanField(array(
                'label' => 'Enable Priority Alerts',
                'default' => true,
                'configuration' => array(
                    'desc' => 'Send email notifications when a ticket priority increases.'
                )
            )),
           
        );
    }
    }
}

   

