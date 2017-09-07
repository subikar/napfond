<?php
class Mycode_Function_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		//$this->loadLayout();
		//$this->renderLayout();
	}


	public function updateTrackingInfoAction(){
		
		Mage::helper('function/cron')->updateTrackingInfo();
		
	}

	
}
?>