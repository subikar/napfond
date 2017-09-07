<?php


class Mks_Responsivebannerslider_Block_Adminhtml_Responsivebanner extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_responsivebanner";
	$this->_blockGroup = "responsivebannerslider";
	$this->_headerText = Mage::helper("responsivebannerslider")->__("Responsivebanner Manager");
	$this->_addButtonLabel = Mage::helper("responsivebannerslider")->__("Add New Item");
	parent::__construct();
	
	}

}