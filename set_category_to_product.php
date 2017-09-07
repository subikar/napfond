<?php set_time_limit(0);
require_once('app/Mage.php');
umask(0);
Mage::app();

$catArr = array();
 /*
$children = Mage::getModel('catalog/category')->getCategories(34);
foreach ($children as $category) {
    

 
	$children2 = Mage::getModel('catalog/category')->getCategories($category->getId());
	
		foreach ($children2 as $category2) {
			echo 'Shop/Mobile Skin/'.$category->getName().'/'.$category2->getName();
			echo ' , ';
			
			$catArr[] = $category2->getId();
 			
		}
	
	
	
	
	
}
 */
 
$parentCategoryId = 3;
$cat = Mage::getModel('catalog/category')->load($parentCategoryId);
$subcats = $cat->getChildren();
 
 
 
// Get 1 Level sub category of Parent category.
 
 
 
foreach(explode(',',$subcats) as $subCatid)
              {
			  
			  
			  
			  
                $_category = Mage::getModel('catalog/category')->load($subCatid);
 
	$children2 = $_category->getChildren();;
	
		foreach (explode(',',$children2) as $subCatid2) {				
				
				$_category2 = Mage::getModel('catalog/category')->load($subCatid2);
				
			   echo 'Shop/Mobile Case/'.$_category->getName().'/'.$_category2->getName();
			   echo ' , ';

 
  
  }
  
  
  
  
}
 
 
 
/*
 
 
$children = Mage::getModel('catalog/category')->getCategories(3);
foreach ($children as $category) {
   

 
	$children2 = Mage::getModel('catalog/category')->getCategories($category->getId());
	
		foreach ($children2 as $category2) {
			echo 'Shop/Mobile Case/'.$category->getName().'/'.$category2->getName();
			echo ' , ';
			
 			$catArr[] = $category2->getId();
		}
	
	
	
	
	
}
 */
echo 'Gift/Gift For Him/Mobile Case , Gift/Gift For Her/Mobile Case , Gift/Gift For Teens/Mobile Case';

?>