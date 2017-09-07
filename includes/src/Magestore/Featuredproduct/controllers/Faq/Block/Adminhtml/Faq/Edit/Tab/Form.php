<?php

class Magestore_Faq_Block_Adminhtml_Faq_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

	public function getFaq()
	{
		return Mage::registry('faq_data');
	}
 
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('faq_form', array('legend'=>Mage::helper('faq')->__('Item information')));
     
      $store_id = $this->getRequest()->getParam('store');
	  
	  
	  $disabled = false;
	   
	 
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
		  'disabled'  => $disabled,
      ));
	 
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('faq')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
		  'disabled'  => $disabled,
		 
      ));

	$fieldset->addField('category_id', 'select', array(
          'label'     => Mage::helper('faq')->__('Category'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'category_id',
		  'values'    => Mage::helper('faq')->getCategoryOptions2($store_id),
		  'disabled'  => $disabled,
      ));
	

     
     
	  $fieldset->addField('most_frequently', 'select', array(
          'label'     => Mage::helper('faq')->__('Is Most Frequently'),
          'name'      => 'most_frequently',
          'values'    => array(
              array(
                  'value'     => 0,
                  'label'     => Mage::helper('faq')->__('False'),
              ),

              array(
                  'value'     => 1,
                  'label'     => Mage::helper('faq')->__('True'),
              ),
          ),
		  'disabled'  => $disabled,
      ));
	 
	 $fieldset->addField('url_key', 'text', array(
          'label'     => Mage::helper('faq')->__('Url Key'),
       
          'required'  => false,
          'name'      => 'url_key',
		  'disabled'  => $store_id?true:false,
		  
      ));	
	 
	  $fieldset->addField('ordering', 'text', array(
          'label'     => Mage::helper('faq')->__('Ordering'),        
          'required'  => false,
          'name'      => 'ordering',
		  'disabled'  => $disabled,
      ));
	 
     
	  
	  $fieldset->addField('description', 'editor', array(
          'name'      => 'description',
          'label'     => Mage::helper('faq')->__('Description'),
          'title'     => Mage::helper('faq')->__('Description'),
          'style'     => 'width:500px; height:300px;',
          'wysiwyg'   => false,
          'required'  => false,
		  'disabled'  => $disabled,
      ));
	  

     
      if ( Mage::getSingleton('adminhtml/session')->getFaqData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getFaqData());
          Mage::getSingleton('adminhtml/session')->setFaqData(null);
      } elseif ( Mage::registry('faq_data') ) {
          $form->setValues(Mage::registry('faq_data')->getData());
      }
      return parent::_prepareForm();
  }
}