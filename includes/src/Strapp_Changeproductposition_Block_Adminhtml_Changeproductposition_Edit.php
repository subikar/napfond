<?php
/**
* Strapp Changeproductposition Module
*
* @category    Strapp
* @package     Strapp_Changeproductposition
**/
?>
<?php 
class Strapp_Changeproductposition_Block_Adminhtml_Changeproductposition_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'changeproductposition';
        $this->_controller = 'adminhtml_changeproductposition';  
        $this->_updateButton('save', 'label', Mage::helper('changeproductposition')->__('Save CSV File'));
         $this->_formScripts[] = "
            function showExportSection() {            
               $('changeproductposition_tabs_export_section_content').style = 'display:block;';	
            }           
        ";       	       
    }

    public function getHeaderText()
    {
        return Mage::helper('changeproductposition')->__('Uplaod CSV File');        
    }
}