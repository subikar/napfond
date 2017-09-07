<?php


class Mks_Rotateproduct_Block_Adminhtml_Rotateproduct extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_rotateproduct";
	$this->_blockGroup = "rotateproduct";
	$this->_headerText = Mage::helper("rotateproduct")->__("Rotateproduct Manager");
	$this->_addButtonLabel = Mage::helper("rotateproduct")->__("Add New Item");
	parent::__construct();
	
	}

}