<?php
/**
* Strapp Changeproductposition Module
*
* @category    Strapp
* @package     Strapp_Changeproductposition
**/
?>
<?php
class Strapp_Changeproductposition_Model_Export_Csv extends Mage_Core_Model_Abstract
{
    const ENCLOSURE = '"';
    const DELIMITER = ",";    
    
    public function exportCategoryProductPosition()
    {	
	$categoryId = Mage::registry('exportCategoryId');
	$category = Mage::getModel('catalog/category')->load($categoryId);
	$fileName = 'Export_Product_Position_For'.$category->getName().'.csv';
	
	$fp = fopen(Mage::getBaseDir('var').'/export/'.$fileName, 'w');
	$this->writeHeadRow($fp);
	$collection = Mage::getModel('catalog/product')->getCollection()->addFieldToFilter('status',Mage_Catalog_Model_Product_Status::STATUS_ENABLED);
	$collection->addCategoryFilter($category, true);
	foreach ($collection as $product)
	{	  
	  $record	=	$this->getProductValues($product,$categoryId);
	  fputcsv($fp,$record, self::DELIMITER, self::ENCLOSURE);
	}
	fclose($fp);	
	
	return $fileName;
    }   
        
    protected function getProductValues($product,$categoryId)
    {
	$sql = "SELECT * FROM catalog_category_product WHERE category_id=".$categoryId." AND product_id=".$product->getId();
	$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
	
	foreach ($connection->fetchAll($sql) as $arr_row) 
	{
	    $position = $arr_row['position'];
	}
			
	return array($categoryId,$product->getSku(),$position);
    }
    
    protected function getHeadRowValues()
    {
        return array(
        'category_id',
        'sku',
        'position'
        );
    }
    
    protected function writeHeadRow($fp)
    {
        fputcsv($fp, $this->getHeadRowValues(), self::DELIMITER, self::ENCLOSURE);
    }
}