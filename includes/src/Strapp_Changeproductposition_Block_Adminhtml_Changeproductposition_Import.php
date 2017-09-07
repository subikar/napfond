<?php
/**
* Strapp Changeproductposition Module
*
* @category    Strapp
* @package     Strapp_Changeproductposition
**/
?>
<?php 
class Strapp_Changeproductposition_Block_Adminhtml_Changeproductposition_Import extends Mage_Core_Block_Template
{        
    public function getImportfilename()
    {	
	$path = Mage::getBaseDir('var').DS.'strapp/changeproductposition'.DS ;
	if(Mage::registry('importFileName')!=' ')
	  return $path.Mage::registry('importFileName');	
    }       
}
?>