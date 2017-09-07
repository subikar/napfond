<?php

class Magestore_Featuredproduct_Block_Adminhtml_Featuredproduct_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('featuredproduct_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('featuredproduct')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('featuredproduct')->__('Item Information'),
          'title'     => Mage::helper('featuredproduct')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('featuredproduct/adminhtml_featuredproduct_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}