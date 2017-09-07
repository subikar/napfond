<?php
class Mycode_Function_Helper_Cron extends Mage_Core_Helper_Abstract
{
	public function __construct() { 

	}	
	public function updateTrackingInfo()
	{
		$shipMent_Track_collection = Mage::getResourceModel('sales/order_shipment_track_collection')->addAttributeToSelect('entity_id')->addAttributeToSelect('parent_id')->addAttributeToSelect('track_number')->addAttributeToSelect('latest_track_info')->addFieldToFilter(array('latest_track_info'),array(array('nlike' => '%Delivered%')));
		
		foreach($shipMent_Track_collection as $shipMent_Track_collection_val){
			$productModel = Mage::getModel('usa/shipping_carrier_fedex')->getTracking($shipMent_Track_collection_val->getData('track_number'),$shipMent_Track_collection_val->getData('parent_id'));
		}
	}
	

    public function underprocessingAction()
    {
		
        if ($order = $this->_initOrder()){
			
			//http://inchoo.net/magento/cancel-pending-orders/
			
			
        			$order->setData('state', "processing");
					$order->setStatus("under_processing");
					$history = $order->addStatusHistoryComment('Order was set to Under Processing by our automation tool.', false);
					$history->setIsCustomerNotified(false);
					$order->save();
		}
        //$this->_redirect('*/sales_order/view', array('order_id' => $order->getId()));
    }

	
	
}

?>