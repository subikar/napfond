<?php
set_time_limit(0);
ini_set('memory_limit','512M');
require_once('app/Mage.php');

Mage::app();

$arr_Mask_Image = array("mobile-case"=>array('id'=>'3','images'=>array("mask.png")),"mobile-skin"=>array('id'=>'34','images'=>array("mask-front.png","mask-back.png")),"tablet-skin"=>array('id'=>'478','images'=>array("mask.png","mask-front.png","mask-back.png")),"laptop-skin"=>array('id'=>'8','images'=>array("mask.png")),"t-shirt"=>array('id'=>'4','images'=>array("mask.png")));

$sizeWidthArr = array();
$sizeHeightArr = array();
$sizeFolerArr = array();
$sizeForCustomizerArr = array();

$resource = Mage::getSingleton('core/resource');

    $readConnection = $resource->getConnection('core_read');

    $writeConnection = $resource->getConnection('core_write');

$query = 'SELECT * FROM `a_mask_image_dim`';

$results = $readConnection->fetchAll($query);

foreach($results as $results_key=>$results_val)
{
	$sizeWidthArr[] = $results_val["width"];
	$sizeHeightArr[] = $results_val["height"];
	$sizeFolerArr[] = $results_val["foldername"];
	$sizeForCustomizerArr[] = $results_val["for_customizer"];
}



foreach($arr_Mask_Image as $arr_Mask_Image_Key=>$arr_Mask_Image_Val){
	generateMaskImage($arr_Mask_Image_Val,$arr_Mask_Image_Key,'');
}







function generateMaskImage($catIdInfo, $parentMaskImageFolderName, $maskImageFolderName)
{
	
	$catId = $catIdInfo["id"];	
	$cat = Mage::getModel('catalog/category')->load($catId);
	//$maskImageFolderName = $cat->getUrlKey();
	
	
	
	$folderPath = Mage::getBaseDir('media').'/blank_images/'.$parentMaskImageFolderName.'/'.$maskImageFolderName;
	
		/*if(is_dir($folderPath) && $maskImageFolderName!='') {
			
			
			
		}*/

	$subcats = $cat->getChildren();



				foreach(explode(',',$subcats) as $subCatid)
				{				  
				  $_category = Mage::getModel('catalog/category')->load($subCatid);
				  				  
				  if($_category->getIsActive()) {
					
					$sub_cat = Mage::getModel('catalog/category')->load($_category->getId());
					$sub_subcats = $sub_cat->getChildren();
					
					if(trim($sub_subcats) == ''){
											
					}
										
								foreach(explode(',',$sub_subcats) as $sub_subCatid)
								{
									  $_sub_category = Mage::getModel('catalog/category')->load($sub_subCatid);
									  if($_sub_category->getIsActive()) {
										  echo $parentMaskImageFolderName.' => '.$_sub_category->getName() . ' => ' .$_sub_category->getUrlKey();
										  echo '<br/>';										  
										  //generateMaskImage($catIdInfo, $parentMaskImageFolderName, $_sub_category->getUrlKey());
										  createMaskImage($catIdInfo, $parentMaskImageFolderName , $_sub_category->getUrlKey());
									  }
								}
				  }
				  
				}





}


function createMaskImage($catIdInfo, $parentMaskImageFolderName , $maskImageFolderName)
{
	global $sizeWidthArr;
	global $sizeHeightArr;
	global $sizeFolerArr;
	global $sizeForCustomizerArr;	
	
	$catId = $catIdInfo["id"];
	$imageInfo = $catIdInfo["images"];
	$cat = Mage::getModel('catalog/category')->load($catId);
	$urlKey = $cat->getUrlKey();
	
	$folderURL = Mage::getBaseUrl('media').'/blank_images/'.$parentMaskImageFolderName.'/'.$maskImageFolderName;
	$folderPath = Mage::getBaseDir('media').'/blank_images/'.$parentMaskImageFolderName.'/'.$maskImageFolderName;
	
	foreach($imageInfo as $imageName){
		
		if(!is_file($folderPath.'/'.$imageName))
		continue;
		foreach($sizeWidthArr as $sizeWidthArrKey=>$sizeWidthArrVal){
			
			$width = $sizeWidthArrVal;
			$height = $sizeHeightArr[$sizeWidthArrKey];
			$resize_folderName = $sizeFolerArr[$sizeWidthArrKey];
			$for_customizer = $sizeForCustomizerArr[$sizeWidthArrKey];
			
			if($for_customizer=='y')
				$for_customizer = 'yes';
			
			Mage::helper('function')->resizeImg($imageName, $width, $height,$folderURL,$folderPath,$resize_folderName,$for_customizer);
			
		}
	}
	
}

?>