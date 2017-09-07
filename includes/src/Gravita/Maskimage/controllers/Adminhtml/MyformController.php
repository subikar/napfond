<?php
class Gravita_Maskimage_Adminhtml_MyformController extends Mage_Adminhtml_Controller_Action
{
	private $sizeWidthArr;
	private $sizeHeightArr;
	private $sizeFolerArr;
	private $sizeForCustomizerArr;
	
	
	public function indexAction()
    {
		$this->loadLayout()->renderLayout();		
	}
	
	public function postAction()
    {
		$post = $this->getRequest()->getPost();
		
        try {
            if (empty($post)) {
                Mage::throwException($this->__('Invalid form data.'));
            }
		if($post["categorylist"])
		$_categoryPost = Mage::getModel('catalog/category')->load($post["categorylist"]);
	
			if(is_object($_categoryPost))
			$maskImageCategoryPath = explode('/',$_categoryPost->getPath());
			else
			$maskImageCategoryPath = array();


	$resource = Mage::getSingleton('core/resource');

    $readConnection = $resource->getConnection('core_read');

    $writeConnection = $resource->getConnection('core_write');

	$query = 'SELECT * FROM `a_mask_image_dim`';

	$results = $readConnection->fetchAll($query);

			foreach($results as $results_key=>$results_val)
			{
				$this->sizeWidthArr[] = $results_val["width"];
				$this->sizeHeightArr[] = $results_val["height"];
				$this->sizeFolerArr[] = $results_val["foldername"];
				$this->sizeForCustomizerArr[] = $results_val["for_customizer"];
			}
		
		
		if(in_array(3,$maskImageCategoryPath))			
			$arr_Mask_Image = array("mobile-case"=>array('id'=>'3','images'=>array("mask.png")));
		elseif(in_array(34,$maskImageCategoryPath))	
			$arr_Mask_Image = array("mobile-skin"=>array('id'=>'34','images'=>array("mask-front.png","mask-back.png")));
		elseif(in_array(478,$maskImageCategoryPath))
			$arr_Mask_Image = array("tablet-skin"=>array('id'=>'478','images'=>array("mask.png","mask-front.png","mask-back.png")));
		elseif(in_array(8,$maskImageCategoryPath))	
			$arr_Mask_Image = array("laptop-skin"=>array('id'=>'8','images'=>array("mask.png")));
		elseif(in_array(4,$maskImageCategoryPath))	
			$arr_Mask_Image = array("t-shirt"=>array('id'=>'4','images'=>array("mask.png")));
		else	
			$arr_Mask_Image = array("mobile-case"=>array('id'=>'3','images'=>array("mask.png")),"mobile-skin"=>array('id'=>'34','images'=>array("mask-front.png","mask-back.png")),"tablet-skin"=>array('id'=>'478','images'=>array("mask.png","mask-front.png","mask-back.png")),"laptop-skin"=>array('id'=>'8','images'=>array("mask.png")),"t-shirt"=>array('id'=>'4','images'=>array("mask.png")));
			
			foreach($arr_Mask_Image as $arr_Mask_Image_Key=>$arr_Mask_Image_Val){
				$this->generateMaskImage($arr_Mask_Image_Val,$arr_Mask_Image_Key,'',$_categoryPost);
			}
			
            $message = $this->__('Mask image generated successfully.');
            Mage::getSingleton('adminhtml/session')->addSuccess($message);
	} catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*');	
	
	
	}
	
	
public function generateMaskImage($catIdInfo, $parentMaskImageFolderName, $maskImageFolderName, $_categoryPost)
{
	
	$catId = $catIdInfo["id"];	
	$cat = Mage::getModel('catalog/category')->load($catId);
	//$maskImageFolderName = $cat->getUrlKey();
	
	$folderPath = Mage::getBaseDir('media').'/blank_images/'.$parentMaskImageFolderName.'/'.$maskImageFolderName;
	
		/*if(is_dir($folderPath) && $maskImageFolderName!='') {}*/

	$subcats = $cat->getChildren();
	if(is_object($_categoryPost))
	$maskImageCategoryPath = explode('/',$_categoryPost->getPath());
	else
	$maskImageCategoryPath = array();

	$subcatsPath = explode(',',$subcats);
	
	$subcatsPath = array_values(array_intersect($maskImageCategoryPath,$subcatsPath));

	
				foreach($subcatsPath as $subCatid)
				{				  
				  $_category = Mage::getModel('catalog/category')->load($subCatid);
				  				  
				  if($_category->getIsActive()) {
					
					$sub_cat = Mage::getModel('catalog/category')->load($_category->getId());
					$sub_subcats = $sub_cat->getChildren();
					
					if(trim($sub_subcats) == ''){
											
					}
							
							
							if(is_object($_categoryPost))
								$sub_subcats = array($_categoryPost->getId());
							else
								$sub_subcats = 	explode(',',$sub_subcats);
							
								foreach($sub_subcats as $sub_subCatid)
								{
									  $_sub_category = Mage::getModel('catalog/category')->load($sub_subCatid);
									  if($_sub_category->getIsActive()) {
										  //echo $parentMaskImageFolderName.' => '.$_sub_category->getName() . ' => ' .$_sub_category->getUrlKey();
										  //echo '<br/>';										  
										  //generateMaskImage($catIdInfo, $parentMaskImageFolderName, $_sub_category->getUrlKey());
										  $this->createMaskImage($catIdInfo, $parentMaskImageFolderName , $_sub_category->getUrlKey());
									  }
								}
				  }
				  
				}
}	

public function createMaskImage($catIdInfo, $parentMaskImageFolderName , $maskImageFolderName)
{
	$catId = $catIdInfo["id"];
	$imageInfo = $catIdInfo["images"];
	$cat = Mage::getModel('catalog/category')->load($catId);
	$urlKey = $cat->getUrlKey();
	
	$folderURL = Mage::getBaseUrl('media').'/blank_images/'.$parentMaskImageFolderName.'/'.$maskImageFolderName;
	$folderPath = Mage::getBaseDir('media').'/blank_images/'.$parentMaskImageFolderName.'/'.$maskImageFolderName;
	
	foreach($imageInfo as $imageName){		
		if(!is_file($folderPath.'/'.$imageName))
		{
			if (!file_exists($folderPath)) {
					mkdir($folderPath, 0777, true);
			}
		}

		if($imageName == 'mask.png'){
			$maskImageName = $_FILES['mask_image1']['name'];
			if($maskImageName!=''){
			copy($_FILES['mask_image1']['tmp_name'],$folderPath.'/'.$imageName);			
			chmod($folderPath.'/'.$imageName,0777);
			}			
		}	
		elseif($imageName == 'mask-front.png'){
			$maskImageName = $_FILES['mask_image1_1']['name'];
			if($maskImageName!=''){
			copy($_FILES['mask_image1_1']['tmp_name'],$folderPath.'/'.$imageName);			
			chmod($folderPath.'/'.$imageName,0777);
			}			
		}	
		elseif($imageName == 'mask-back.png'){
			$maskImageName = $_FILES['mask_image1_2']['name'];
			if($maskImageName!=''){
			copy($_FILES['mask_image1_2']['tmp_name'],$folderPath.'/'.$imageName);			
			chmod($folderPath.'/'.$imageName,0777);
			}			
		}	
		
		//continue;
		foreach($this->sizeWidthArr as $sizeWidthArrKey=>$sizeWidthArrVal){
			
			$width = $sizeWidthArrVal;
			$height = $this->sizeHeightArr[$sizeWidthArrKey];
			$resize_folderName = $this->sizeFolerArr[$sizeWidthArrKey];
			$for_customizer = $this->sizeForCustomizerArr[$sizeWidthArrKey];
			
			if($for_customizer=='y')
				$for_customizer = 'yes';
			Mage::helper('function')->resizeImg($imageName, $width, $height,$folderURL,$folderPath,$resize_folderName,$for_customizer);			
		}
	}	
}

	
	
}