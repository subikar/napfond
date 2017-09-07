<?php

class Magestore_Featuredproduct_Block_Adminhtml_Featuredproduct_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('featuredproduct_form', array('legend'=>Mage::helper('featuredproduct')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('featuredproduct')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('featuredproduct')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('featuredproduct')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('featuredproduct')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('featuredproduct')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('featuredproduct')->__('Content'),
          'title'     => Mage::helper('featuredproduct')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getFeaturedproductData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getFeaturedproductData());
          Mage::getSingleton('adminhtml/session')->setFeaturedproductData(null);
      } elseif ( Mage::registry('featuredproduct_data') ) {
          $form->setValues(Mage::registry('featuredproduct_data')->getData());
      }
      return parent::_prepareForm();
  }
}