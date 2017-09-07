<?php
class Gravita_Search_Adminhtml_SearchformController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
		$write = Mage::helper('function/more')->getDbWriteObject();	
		$read = Mage::helper('function/more')->getDbReadObject();

			//////////////////////////////Code For Matching Product Title//////////////////////////////


			$category_id = 1141; # Change category ID here.
			$category = Mage::getModel('catalog/category')->load($category_id);
			$products = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToSelect('*')
			->addCategoryFilter($category)
			->load();
			foreach($products as $product){
			//echo '<a href="'.$product->getProductUrl().'">'.$product->getName()."</a><br>";
			//echo $product->getName();
			///echo '<br/>';


						$query = 'SELECT id FROM a_search_matching_words WHERE TRIM(LOWER(match_word)) ="'.trim(strtolower($product->getName())).'" and match_word_type="prdtitle" LIMIT 1'; 
						$id = $read->fetchOne($query);
						
						if($id != ''){
							//$match_cat_child_id = $match_cat_child_id.','.$category->getId();
							$query = "update a_search_matching_words set match_word='".trim(strtolower(addslashes($product->getName())))."',match_word_type='prdtitle' where id='".$id."'";				
							$write->query($query);	
						}
						else{
							$match_cat_child_id = $category->getId();
							$query = "insert into a_search_matching_words set match_word='".trim(strtolower(addslashes($product->getName())))."',match_word_type='prdtitle'";
							$write->query($query);				
						}



			}


			$attribute_code = "case_style"; 
			$attribute_details = Mage::getSingleton("eav/config")->getAttribute("catalog_product", $attribute_code); 
			$options = $attribute_details->getSource()->getAllOptions(false); 

			foreach($options as $option){
				

						$query = 'SELECT id FROM a_search_matching_words WHERE match_word_db_id ="'.$option["value"].'" and match_word_type="style" LIMIT 1'; 
						$id = $read->fetchOne($query);
						
						
						
						
						if($id != ''){
							//$match_cat_child_id = $match_cat_child_id.','.$category->getId();
							$query = "update a_search_matching_words set match_word='".trim($option["label"])."',match_word_type='style',match_word_db_id='".$option["value"]."' where id='".$id."'";				
							$write->query($query);	
						}
						else{
							//$match_cat_child_id = $category->getId();
							$query = "insert into a_search_matching_words set match_word='".trim($option["label"])."',match_word_type='style',match_word_db_id='".$option["value"]."'";
							$write->query($query);				
						}

			}




			//////////////////////////////Code For Matching Cateogry///////////////////////////////
			$this->getChildCategories(24);
			//////////////////////////////Code for matching style word/////////////////////////////






	
        $this->loadLayout()->renderLayout();
    } 
	
	
	
		public function getsubCats($catId,$subCat)
		{
			$write = Mage::helper('function/more')->getDbWriteObject();	
			$read = Mage::helper('function/more')->getDbReadObject();

			
			$_category = Mage::getModel('catalog/category')->load($catId);
			$children  = Mage::getModel('catalog/category')->getCategories($subCat->getId());
			
					$query = 'SELECT match_cat_child_id FROM a_search_matching_words WHERE match_word_db_id ="'.$catId.'" and match_word_type="category" LIMIT 1'; 
					$match_cat_child_id = $read->fetchOne($query);
					
					if($match_cat_child_id != ''){
						$match_cat_child_id = $match_cat_child_id.','.$subCat->getId();
						$query = "update a_search_matching_words set match_cat_child_id = '".$match_cat_child_id."' where match_word_db_id ='".$catId."' and match_word_type='category'";				
						$write->query($query);	
					}
					else{
						$match_cat_child_id = $subCat->getId();
						$query = "insert into a_search_matching_words set match_word='".trim($_category->getName())."',match_word_type='category',match_word_db_id='".$catId."',match_cat_child_id = '".$match_cat_child_id."'";
						$write->query($query);				
					}
					
					
			if($children->count() > 0)
			{
				foreach ($children as $category2){
					$this->getsubCats($catId,$category2);					
				}
			}		
		}

		public function getChildCategories($catId,$level=''){
			
			$write = Mage::helper('function/more')->getDbWriteObject();	
			$read = Mage::helper('function/more')->getDbReadObject();
			
			$_category = Mage::getModel('catalog/category')->load($catId);
			$children = Mage::getModel('catalog/category')->getCategories($catId);
			

			if($children->count() > 0)
			{
				foreach ($children as $category) {
					$this->getsubCats($catId,$category);
					$this->getChildCategories($category->getId(),$category->getLevel());
				}
			}else{
					$path = explode('/',$_category->getPath());
					//echo '<br/>';
					$baseURLMedia = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
						///////////Condition for mask image/////////
						
						$id_cat_path = '';
						
						$id_cat_path = '';
						
						if(in_array(34,$path)){
							$id_cat_path = 'mobile-skin';
							$maskImagePath = $baseURLMedia.'blank_images/mobile-skin/'.$_category->getUrlKey().'/resized/mask-back.png';
						}
						elseif(in_array(3,$path)){
							$id_cat_path = 'mobile-case';
							$maskImagePath = $baseURLMedia.'blank_images/mobile-case/'.$_category->getUrlKey().'/resized/mask.png';
						}
						elseif(in_array(8,$path)){
							$maskImagePath = '';
						}
						elseif(in_array(479,$path) || in_array(518,$path)){
							$id_cat_path = 'tablet-skin';
							$maskImagePath = $baseURLMedia.'blank_images/tablet-skin/'.$_category->getUrlKey().'/resized/mask-back.png';
						}
						else{
							$maskImagePath = '';
						}
						
						
					if($id_cat_path!=''){
										$imageName_1 = '';
										$imageName_2 = '';
									
										if($id_cat_path == 'mobile-case'){
											$imageName_1 = 'mask.png';
											$imageName_2 = '';
										}	
										elseif($id_cat_path == 'mobile-skin')
										{
											$imageName_1 = 'mask-front.png';
											$imageName_2 = 'mask-back.png';
										}
										elseif($id_cat_path == 'tablet-skin'){
											$imageName_1 = 'mask-back.png';
											$imageName_2 = '';			
										}
			
						if($imageName_1 != '' || $imageName_2 != ''){
				  
						  $id_cat_path = $id_cat_path . $_category->getUrlKey();
						
						  $folderURL = Mage::helper('function')->graGetFolderUrl($id_cat_path);		 
						  $folderPath = Mage::helper('function')->graGetFolderPath($id_cat_path);
						  
						  if($imageName_1 != ''){
							if(!is_file($folderPath . DS . 'resized' . DS . $imageName_1))
							 Mage::helper('function')->resizeImg($imageName_1, 400, 400,$folderURL,$folderPath,'resized');	   
						  }	
						  
						  if($imageName_2 != ''){
							if(!is_file($folderPath . DS . 'resized' . DS . $imageName_2))
							 Mage::helper('function')->resizeImg($imageName_2, 400, 400,$folderURL,$folderPath,'resized');	   
						  }	  
						}
			  
					}
						
					$query = 'SELECT id FROM a_search_matching_words WHERE match_word_db_id ="'.$catId.'" and match_word_type="category" LIMIT 1'; 
					$match_cat_child_id = $read->fetchOne($query);
					
					if($match_cat_child_id != ''){
						$match_cat_child_id = '';
						$query = "update a_search_matching_words set match_cat_child_id = '".$match_cat_child_id."',match_cat_mask_path='".$maskImagePath."',match_cat_url_key='".$_category->getUrlKey()."' where match_word_db_id ='".$catId."' and match_word_type='category'";				
						$write->query($query);	
					}
					else{
						$match_cat_child_id = '';
						$query = "insert into a_search_matching_words set match_word='".trim($_category->getName())."',match_word_type='category',match_word_db_id='".$catId."',match_cat_child_id = '',match_cat_mask_path='".$maskImagePath."',match_cat_url_key='".$_category->getUrlKey()."'";
						$write->query($query);	
						
						
					}		
			}
		}
	public function updateMaskImagesAction(){		
		
		
		
		
	}
}