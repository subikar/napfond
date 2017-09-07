<?php
/**
* Strapp Changeproductposition Module
*
* @category    Strapp
* @package     Strapp_Changeproductposition
**/
?>
<?php 
class Strapp_Changeproductposition_Block_Adminhtml_Changeproductposition_Edit_Tab_Processcsv extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('strapp/changeproductposition/processcsv.phtml');
    }
    
    public function getRunButtonHtml()
    {
        $html = '';
        $html .= $this->getLayout()->createBlock('adminhtml/widget_button')->setType('button')
            ->setClass('save')->setLabel($this->__('Import CSV in Popup'))
            ->setOnClick('runProfile(true)')
            ->toHtml();

        return $html;
    }
    
    public function getImportedCsvFiles()
    {      
        $files = array();
        $path = Mage::getBaseDir('var').DS.'strapp/changeproductposition'.DS;
        if (!is_readable($path)) 
        {
            return $files;
        }
        
        $dir = dir($path);
        
        while (false !== ($entry = $dir->read())) 
        {        
            if($entry != '.'&& $entry != '..'&& in_array(strtolower(substr($entry, strrpos($entry, '.')+1)), array($this->getParseType())))
	    {
		$files[] = $entry;
	    }
        }
        sort($files);
        $dir->close();
        
        return $files;
    }
    
    public function getParseType()
    {
        return 'csv';
    }
}