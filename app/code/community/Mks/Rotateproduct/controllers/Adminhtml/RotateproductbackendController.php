<?php
class Mks_Rotateproduct_Adminhtml_RotateproductbackendController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("About Us"));
	   $this->renderLayout();
    }
}