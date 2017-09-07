<?php
/**
* Strapp Changeproductposition Module
*
* @category    Strapp
* @package     Strapp_Changeproductposition
**/
?>
<?php 
class Strapp_Changeproductposition_Block_Adminhtml_Changeproductposition_Edit_Tab_Exportcsv extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('strapp/changeproductposition/exportcsv.phtml');
    }
    
    public function getRunButtonHtml()
    {
        $html = '';
        $html .= $this->getLayout()->createBlock('adminhtml/widget_button')->setType('button')
            ->setClass('save')->setLabel($this->__('Exportport CSV '))
            ->setOnClick('exportCsv()')
            ->toHtml();

        return $html;
    }
    
    public function getCategoriesDropdown() 
    {
	$categoriesArray = Mage::getModel('catalog/category')
        ->getCollection()
        ->addAttributeToSelect('name')
        ->addAttributeToSort('path', 'asc')
        ->addFieldToFilter('is_active', array('eq'=>'1'))
        ->load()
        ->toArray();

	foreach ($categoriesArray as $categoryId => $category) 
	{
	  if (isset($category['name'])) 
	  {
            $categories[] = array(
                'label' => $category['name'],
                'level'  =>$category['level'],
                'value' => $categoryId
            );
	  }
	}
	
	return $categories;
    }
}
