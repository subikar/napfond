<?php

    class Biztech_Trackorder_Model_System_Config_Frontend_Widgetsettings 
    {
        public function toOptionArray()
        {
            return array(                
                array('value' => 'left',
                      'label' => Mage::helper('trackorder')->__('Left Column')),
                array('value' => 'right',
                      'label' => Mage::helper('trackorder')->__('Right Column')),
            );
        }

}