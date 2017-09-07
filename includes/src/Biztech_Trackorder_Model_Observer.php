<?php

class Biztech_Trackorder_Model_Observer {

    private static $_handleTrackLinkCounter = 1;

    public function checkstatus(Varien_Event_Observer $observer) {
        $params = Mage::app()->getRequest()->getParam('groups');
        $configValue = $params['trackorder_general']['fields']['enabled']['value'];
        if ($configValue == 0) {
            $trackOrderConfig = new Mage_Core_Model_Config();
            $trackOrderConfig->saveConfig('trackorder/trackorder_general/toplinks', "0");
            $trackOrderConfig->saveConfig('trackorder/trackorder_general/topmenu', "0");
            $trackOrderConfig->saveConfig('trackorder/trackorder_general/sendtrack_link', "0");
        }

        $showWidget = $params['trackorder_general']['fields']['show_widget']['value'];
       
            
           if(version_compare(Mage::getVersion(), '1.5.1.0', '>')) { 
                $widgetInstance  = Mage::getModel('widget/widget_instance')->getCollection()->addFieldToFilter('instance_type' , 'trackorder/trackwidget')->getData();
            }
            else
            {
                $widgetInstance  = Mage::getModel('widget/widget_instance')->getCollection()->addFieldToFilter('type' , 'trackorder/trackwidget')->getData();
            }
            
            
            $widgetInstanceID = $widgetInstance[0]['instance_id'];
            $widgetParameters = array(
                'param1' => 'This is some value from the widget form',
                'template' => 'trackorder/trackwidget.phtml'
            );

            $instance = Mage::getModel('widget/widget_instance')->load($widgetInstanceID);
            $instance->setData(array(
                        'instance_id' => $widgetInstanceID,
                        'type' => 'trackorder/trackwidget',
                        'package_theme' => 'default/default',
                        'title' => 'Track Widget',
                        'store_ids' => '0', // or comma separated list of ids
                        'sort_order' => '0',
                        'widget_parameters' => serialize($widgetParameters),
                        'page_groups' => array(array(
                                'page_group' => 'all_pages',
                                'all_pages' => array(
                                    'page_id' => null,
                                    'group' => 'all_pages',
                                    'layout_handle' => 'default',
                                    'block' => $showWidget,
                                    'for' => 'all',
                                    'template' => $widgetParameters['template'],
                                )
                            ))
                    ))->save();
        
    }

    public function addlinkAdmin(Varien_Event_Observer $observer) {

        if (Mage::app()->getRequest()->getControllerName() == 'sales_order_create' || Mage::app()->getRequest()->getControllerName() == 'sales_order_edit') {
            if (self::$_handleTrackLinkCounter > 1) {
                return $this;
            }

            $order = $observer->getEvent()->getOrder();
		self::$_handleTrackLinkCounter++;
            	if ($order->getTrackLink() == NULL) {
                $trackLink = substr(md5(microtime()), rand(0, 26), 6);
 		
		$order->setTrackLink($trackLink);
	    }
	}	
    }
    
    public function addlinkFront(Varien_Event_Observer $observer) {

        if (Mage::app()->getRequest()->getControllerName() != 'multishipping') {
            if (self::$_handleTrackLinkCounter > 1) {
                return $this;
            }

  		$order = $observer->getEvent()->getOrder();
		self::$_handleTrackLinkCounter++;
            	if ($order->getTrackLink() == NULL) {
                $trackLink = substr(md5(microtime()), rand(0, 26), 6);
                $order->setTrackLink($trackLink);               
            }
	}
    }
    
    
    public function addmultishipLink(Varien_Event_Observer $observer) {

        if (Mage::app()->getRequest()->getControllerName() == 'multishipping') {
                $order = $observer->getEvent()->getOrder();
		
            	if ($order->getTrackLink() == NULL) {
                $trackLink = substr(md5(microtime()), rand(0, 26), 6);
                $order->setTrackLink($trackLink);               
            }
	}
    }

}

?>