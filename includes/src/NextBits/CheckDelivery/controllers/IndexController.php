<?php
class NextBits_CheckDelivery_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction() {
		
		$zip = $this->getRequest()->getParam('zipcode');
		$pincodearray = array();
		$pindata = Mage::getStoreConfig('checkdelivery/general/pincode');
		$pincodearray = explode(",", $pindata);
		$success = Mage::getStoreConfig('checkdelivery/general/success_messgae');
		$failure = Mage::getStoreConfig('checkdelivery/general/failure_messgae');
		$empty = Mage::getStoreConfig('checkdelivery/general/empty_messgae');
		$successHtml = Mage::getStoreConfig('checkdelivery/general/success_html');
		$failureHtml = Mage::getStoreConfig('checkdelivery/general/failure_html');
		$defaultHtml = Mage::getStoreConfig('checkdelivery/general/default_html');
		
		$trimedZip = trim($zip);
		$response = array();
		if(isset($trimedZip) && !empty($trimedZip)){
			if (in_array($trimedZip, $pincodearray)) {
				$response['message'] = $success;
				$response['color'] = 'green';
				$response['html'] = $successHtml;
				
			}else{
				$response['message'] = $failure;
				$response['color'] = 'red';
				$response['html'] = $failureHtml;
				
			}
		}else{
			$response['message'] = $empty;
			$response['color'] = 'orange';
			$response['html'] = $defaultHtml;
		}
		echo json_encode($response);exit;
    }
}