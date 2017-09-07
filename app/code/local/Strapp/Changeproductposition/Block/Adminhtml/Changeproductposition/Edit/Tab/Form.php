<?php
/**
* Strapp Changeproductposition Module
*
* @category    Strapp
* @package     Strapp_Changeproductposition
**/
?>
<?php 
class Strapp_Changeproductposition_Block_Adminhtml_Changeproductposition_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('changeproductposition_form', array('legend'=>Mage::helper('changeproductposition')->__('Upload and Importing Of CSV')));
           
      $fieldset->addField('csvfile', 'file', array(
          'label'     => Mage::helper('changeproductposition')->__('Csv File'),
          'required'  => false,
          'name'      => 'csvfile',
	  ));
	  	
      $fieldset->addField('exportlink', 'text', array(
          'label'     => '',
          'style'   => "border:none;cursor:pointer;background:none;color:#EA7601;text-decoration:underline;font-size:18px;width:370px;",          
          'value'  => 'Please click here to download sample csv file',
          'onclick' => 'showExportSection()',
          'after_element_html' => ''
        ));
      
      if ( Mage::getSingleton('adminhtml/session')->getChangeproductpositionData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getChangeproductpositionData());
          Mage::getSingleton('adminhtml/session')->setChangeproductpositionData(null);
      } 
      
      elseif ( Mage::registry('changeproductposition_data') ) 
      {
          $form->setValues(Mage::registry('changeproductposition_data')->getData());
      }
      
      return parent::_prepareForm();
  }
}