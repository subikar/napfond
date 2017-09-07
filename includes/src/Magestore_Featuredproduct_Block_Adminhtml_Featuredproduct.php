<?php
class Magestore_Featuredproduct_Block_Adminhtml_Featuredproduct extends Mage_Adminhtml_Block_Widget_Grid_Container
{
   public function __construct()
  {
    $this->_controller = 'adminhtml_featuredproduct';//path to block
    $this->_blockGroup = 'featuredproduct';//name module
    $this->_headerText = Mage::helper('featuredproduct')->__('Featured Product Manager');
   // $this->_addButtonLabel = Mage::helper('featuredproduct')->__('Add Item');
    parent::__construct();
	$this->_removeButton('add', 'label');
  } 
   
}
