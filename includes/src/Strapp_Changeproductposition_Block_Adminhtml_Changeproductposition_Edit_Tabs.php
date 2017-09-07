<?php
/**
* Strapp Changeproductposition Module
*
* @category    Strapp
* @package     Strapp_Changeproductposition
**/
?>
<?php
class Strapp_Changeproductposition_Block_Adminhtml_Changeproductposition_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('changeproductposition_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('changeproductposition')->__('Upload & Process CSV'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('changeproductposition')->__('Upload CSV'),
          'title'     => Mage::helper('changeproductposition')->__('Upload CSV'),
          'content'   => $this->getLayout()->createBlock('changeproductposition/adminhtml_changeproductposition_edit_tab_form')->toHtml(),
      ));
     
      $this->addTab('process_section', array(
          'label'     => Mage::helper('changeproductposition')->__('Process CSV'),
          'title'     => Mage::helper('changeproductposition')->__('Process CSV'),
          'content'   => $this->getLayout()->createBlock('changeproductposition/adminhtml_changeproductposition_edit_tab_processcsv')->toHtml(),
      ));
      
      $this->addTab('export_section', array(
          'label'     => Mage::helper('changeproductposition')->__('Export CSV'),
          'title'     => Mage::helper('changeproductposition')->__('Export CSV'),
          'content'   => $this->getLayout()->createBlock('changeproductposition/adminhtml_changeproductposition_edit_tab_exportcsv')->toHtml(),
      ));
      
      return parent::_beforeToHtml();
  }
}