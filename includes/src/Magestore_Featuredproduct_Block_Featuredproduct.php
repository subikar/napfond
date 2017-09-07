<?php
class Magestore_Featuredproduct_Block_Featuredproduct extends Mage_Catalog_Block_Product_Abstract
{
	public function __construct()
    {
        parent::__construct();

        $storeId    = Mage::app()->getStore()->getId();
		$number = Mage::getStoreConfig('featuredproduct/general/show_product_number');

        $products = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('*')
            //->addAttributeToSelect(array('name', 'price', 'small_image')) //edit to suit tastes
            ->setStoreId($storeId)
            ->addStoreFilter($storeId)
            ->addAttributeToFilter('fb_product', array('eq' => '1'))
			->setPageSize($number)
			->setCurPage(1)
			->load();
        
        //Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        //Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);

        $this->setProductCollection($products);
    }
	
	
	  public function getProductCollection2($category)
	  {
		  parent::getProductCollection();
		  $storeId    = Mage::app()->getStore()->getId();
		  $number = Mage::getStoreConfig('featuredproduct/general/show_product_number');  
		  $products = Mage::getModel('catalog/category')->load($category)
			  ->getProductCollection()
			  ->addAttributeToSelect('*')
			  //->addAttributeToSelect(array('name', 'price', 'small_image')) //edit to suit tastes
			  ->setStoreId($storeId)
			  ->addStoreFilter($storeId)
			  ->addAttributeToFilter('fb_product', array('eq' => '1'))
			  ->setPageSize($number)
			  ->setCurPage(1)
			  ->load();
		  
		  //Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
		  //Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
  
		  return $products;
	  }
}