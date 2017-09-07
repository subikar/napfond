<?php
class Mks_Responsivebannerslider_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Mks_Responsivebannerslider"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("mks_responsivebannerslider", array(
                "label" => $this->__("Mks_Responsivebannerslider"),
                "title" => $this->__("Mks_Responsivebannerslider")
		   ));

      $this->renderLayout(); 
	  
    }
}