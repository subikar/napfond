<?php

class Magestore_Faq_Block_Adminhtml_Category_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  
  public function getCategory()
  {
	return Mage::registry('faqcategory_data');
  }
  
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('category_form', array('legend'=>Mage::helper('faq')->__('Category information')));
	  	  
       $store_id = $this->getRequest()->getParam('store');
	   
	  $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('faq')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('faq')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('faq')->__('Disabled'),
              ),
          ),
		  'disabled' => false,
      ));
	  
	  $fieldset->addField('name', 'text', array(
          'label'     => Mage::helper('faq')->__('Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'name',
		  
		  'disabled' => false,
      ));

	  
	  $fieldset->addField('url_key', 'text', array(
          'label'     => Mage::helper('faq')->__('Url Key'),       
          'required'  => false,
          'name'      => 'url_key',
		  
		  'disabled' =>  $store_id?true:false,
      ));

	  
	  $fieldset->addField('ordering', 'text', array(
          'label'     => Mage::helper('faq')->__('Ordering'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'ordering',
		  'disabled' => false,
      ));
      
      
     
      $fieldset->addField('description', 'editor', array(
          'name'      => 'description',
          'label'     => Mage::helper('faq')->__('Description'),
          'title'     => Mage::helper('faq')->__('Description'),
          'style'     => 'width:500px; height:100px;',
          'wysiwyg'   => false,
          'required'  => true,
		  'disabled' => false,
      ));
     
		
      if ( Mage::getSingleton('adminhtml/session')->getFaqcategoryData() )
      {
          $data = Mage::getSingleton('adminhtml/session')->getFaqcategoryData();
		  $form->setValues($data);
          Mage::getSingleton('adminhtml/session')->setFaqcategoryData(null);
      } elseif ( Mage::registry('faqcategory_data') ) {
	      $data = Mage::registry('faqcategory_data')->getData() ;
          $form->setValues($data);
      }	 
      return parent::_prepareForm();
  }
}