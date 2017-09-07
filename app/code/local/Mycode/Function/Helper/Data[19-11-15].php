<?php
class Mycode_Function_Helper_Data extends Mage_Core_Helper_Abstract
{ 
	 public function resizeImg($fileName, $width, $height = '',$folderURL,$folderPath,$reizeFolderName='resized',$for_customizer='')
	 {
			 
			 if(!is_dir($folderPath . DS . "resized")) 
			  mkdir($folderPath . DS . "resized");
			 if(!is_dir($folderPath . DS . "resized1000")) 
			  mkdir($folderPath . DS . "resized1000");
			 
			$imageURL = $folderURL . DS . $fileName;
		 
			$basePath = $folderPath . DS . $fileName;
			
			 $newPath = $folderPath . DS . $reizeFolderName . DS . $fileName;
			//if width empty then return original size image's URL
			if ($width != '') {
				//if image has already resized then just return URL
				if (file_exists($basePath) && is_file($basePath) && !file_exists($newPath)) {
					$imageObj = new Varien_Image($basePath);
					$imageObj->constrainOnly(TRUE);
					$imageObj->keepAspectRatio(FALSE);
					$imageObj->keepFrame(FALSE);
					$imageObj->keepTransparency(TRUE);
					$imageObj->resize($width, $height);
					$imageObj->save($newPath);
				}
				
 
				  $resizedURL = $folderURL . "/" .$reizeFolderName. "/" . $fileName;
				  
				 
				  if($for_customizer == 'yes')
				   {
				   
				   //echo $folderPath . DS . "resized". DS . 'for_customizer';
						if(!is_dir($folderPath . DS . "resized" . DS . 'for_customizer')) 
							mkdir($folderPath . DS . "resized". DS . 'for_customizer');				   
				   
						 $new_filename = $folderPath . DS . $reizeFolderName . DS .'for_customizer'. DS . $fileName;
							list($orig_w, $orig_h) = getimagesize($newPath);
						
						$orig_img = imagecreatefrompng(($newPath));

						$output_w = 468;
						$output_h = 509;

						// determine scale based on the longest edge
						if ($orig_h > $orig_w) {
							$scale = $output_h/$orig_h;
						} else {
							$scale = $output_w/$orig_w;
						}

							// calc new image dimensions
						$new_w =  $width;
						$new_h =  $height;
							
						/*echo "Scale: $scale<br />";
						echo "New W: $new_w<br />";
						echo "New H: $new_h<br />";*/

						// determine offset coords so that new image is centered
						$offest_x = ($output_w - $new_w) / 2;
						$offest_y = ($output_h - $new_h) / 2;

							// create new image and fill with background colour
							
						$mask_out_path = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) .  DS . 'mask-out.png';	
							
						$new_img = imagecreatefrompng(($mask_out_path));
						
						imagesavealpha($new_img, true);
//$bgcolor = imagecolorallocatealpha($new_img, 0, 0, 0, 127);						
						
						//$bgcolor = imagecolorallocate($new_img, 255, 255, 255); // red
						//imagefill($new_img, 0, 0, $bgcolor); // fill background colour

							// copy and resize original image into center of new image
						imagecopyresampled($new_img, $orig_img, ($offest_x), ($offest_y), 0, 0, $new_w, $new_h, $orig_w, $orig_h);
							
							//save it
						imagepng($new_img, $new_filename);
						$resizedURL = $folderURL . "/".$reizeFolderName."/for_customizer/" . $fileName;
				    }
			 } else {
				$resizedURL = $imageURL;
			 }
			 return $resizedURL;
		}
		
	   public function graGetFolderUrl($prdType)
		 {
		    return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) .  '/blank_images/' . $prdType ;
		 }
		 
	   public function graGetFolderPath($prdType,$graBlankImageFolderKey)
		 {
		   return Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) .  DS . 'blank_images' . DS . $prdType ;
		 }
	
	   public function getBlankFrontBackImages($catId,$width=400,$height=400)
		 {
			$_category = Mage::getModel('catalog/category')->load($catId);
	
			$retArrImages = array();	
	
			 if($width==$height)
			 $resizedFolderName = 'resized'.$width;
			 else
			 $resizedFolderName = 'resized'.$width.'_'.$height;
			/***************************Code for fetching blank image**************************/
				$path = $_category->getPath();
				$ids  = explode('/', $path);
						unset($ids[0]);
				$sep='';
				$id_cat_path='';	
				$id_cat_path_counter=0;	
				foreach($ids as $idcat)
				 {
					$id_cat_path_counter++;
					if($id_cat_path_counter <= 2)
					continue;
					
					if((in_array(34,$ids) || in_array(1022,$ids) || in_array(75,$ids) || in_array(3,$ids) || in_array(66,$ids)) && $id_cat_path_counter == 4 /**For handling condition for mobile case and mobile skin**/)
					  continue;
					
					$idcat_obj = Mage::getModel('catalog/category')->load($idcat);		 
					$id_cat_path .= $sep.$idcat_obj->getUrlKey();
					$sep = '/';
				 }
				 
				 //echo $id_cat_path;
				 
				if(in_array(478,$ids) || in_array(8,$ids) || in_array(3,$ids) /**For handling condition for mobile case**/){
				
				  $folderURL = Mage::helper('function')->graGetFolderUrl($id_cat_path);		 
				  $folderPath = Mage::helper('function')->graGetFolderPath($id_cat_path);
				
				  if(!is_file($folderPath . DS . $resizedFolderName . DS . 'mask.png'))
				   $blank_backImagePath = Mage::helper('function')->resizeImg('mask.png', 450, 450,$folderURL,$folderPath,$resizedFolderName);	   
				  else 	
				   $blank_backImagePath = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL).'media/blank_images/'.$id_cat_path.'/'.$graBlankImageFolderKey.'/'.$resizedFolderName.'/mask.png';
				   
				   
				   $retArrImages[] = $blank_backImagePath;
				   
				 }
				else if(in_array(34,$ids))
				 { 
					$folderURL = Mage::helper('function')->graGetFolderUrl($id_cat_path);		 
					$folderPath = Mage::helper('function')->graGetFolderPath($id_cat_path);
				   
				  if(!is_file($folderPath . DS . $resizedFolderName . DS . 'mask-front.png'))
				   $blank_frontImagePath = Mage::helper('function')->resizeImg('mask-front.png', 450, 450,$folderURL,$folderPath,$resizedFolderName);	   
				  else	  
				   $blank_frontImagePath = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL).'media/blank_images/'.$id_cat_path.'/'.$resizedFolderName.'/mask-front.png';	
				   
				   
				   $retArrImages[] = $blank_frontImagePath;
				   
				  if(!is_file($folderPath . DS . $resizedFolderName . DS . 'mask-back.png')) 
				   $blank_backImagePath = Mage::helper('function')->resizeImg('mask-back.png', 450, 450,$folderURL,$folderPath,$resizedFolderName);	   
				  else 
				   $blank_backImagePath = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL).'media/blank_images/'.$id_cat_path.'/'.$resizedFolderName.'/mask-back.png'; 
				   
				   
				   $retArrImages[] = $blank_backImagePath;
				   
				 }	
			/****************************************************************************************/	
	
	
			return $retArrImages;
	
		 }
	
		public function getUrlPath($product, $category=null)
		{
			  $path = $product->getUrlKey().'.html';
				//$path = $product->getRequestPath();
			if (is_null($category)) {
				/** @todo get default category */
				return $path;
			} elseif (!$category instanceof Mage_Catalog_Model_Category) {
				Mage::throwException('Invalid category object supplied');
			}


			return Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL).Mage::helper('catalog/category')->getCategoryUrlPath($category->getUrlPath())
				. '/' . $path;
		}

////////////////////////////Hunny Function for creating menu////////////////////////////////////////////////////



function  get_categories($categories) {
  $array= '<ul class="nav">';
  $i=0;	
  foreach($categories as $category) {
	  if($i == 4){$i++;continue;}
	  if($i>5){break;}	  
	$cat = Mage::getModel('catalog/category')->load($category->getId());
	$count = $cat->getProductCount();
	
	if($category->getName() == "Laptop Skin"){
	/*$array .= '<li class="dropdown">'.
	'<a href="' . Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL). 'shop/laptop-tablet-skin.html/">
	  <span>Laptop/Tablet Skin' . "</span>
	 </a>\n";*/
	$array .= '<li class="dropdown">'.
	'<a href="javascript:void(0)">
	  <span>Laptop/Tablet Skin' . "</span>
	 </a>\n";
	}
	elseif($category->getName() == "T-Shirt"){
	/*$array .= '<li class="dropdown">'.
	'<a href="' . Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL). 'shop/laptop-tablet-skin.html/">
	  <span>Laptop/Tablet Skin' . "</span>
	 </a>\n";*/
	$array .= '<li class="dropdown">'.
	'<a href="javascript:void(0)">
	  <span>'.$category->getName() . "</span>
	 </a>\n";
	}
	else
	$array .= '<li class="dropdown">'.
	'<a href="' . Mage::getUrl($cat->getUrlPath()). '">
	  <span>' .$category->getName() . "</span>
	 </a>\n";
	 
	 
	 
	 if($category->getName() == 'Laptop Skin')
	 {
		 $array .= Mage::helper('function')->get_dropdown($category->getName());
		 $i++;
		 continue;
	 }
	 else if($category->getName() == 'T-Shirt')
	 {
		 $array .= Mage::helper('function')->get_dropdown1($category->getName());
		 $i++;
		 continue;
	 }
	 else if($category->hasChildren()) {
		 $children = Mage::getModel('catalog/category')->getCategories($category->getId());
	  	 $array .= Mage::helper('function')->get_subcategories($children, $category->getName());
	 }	
	 $array .= '</li>';
	 $i++;
  }
  return  $array . '</ul>';
}

function get_subcategories($sub_categories, $parent) {$array = '<ul class="dropdown-menu mega-menu">';if($parent == 'Mobile Skin'){$array .= '<div class="col-md-9 mega-menu-subLeft case"><div class="dropmenu-label">By Device/Model</div>';}if($parent == 'Mobile Case'){$array .= '<div class="col-md-9 mega-menu-subLeft case"><div class="dropmenu-label">By Device/Model</div>';}

foreach($sub_categories as $sub_category) {
	if($parent == 'Mobile Skin' && !in_array($sub_category->getId(),$this->mobileSkinBrandIncludeInNavigationBottom()))
	 continue;
	if($parent == 'Mobile Case' && !in_array($sub_category->getId(),$this->mobileCaseBrandIncludeInNavigationBottom()))
	 continue;
	
	
	$sub_cat = Mage::getModel('catalog/category')->load($sub_category->getId());$array .= '<li class="mega-menu-column padding-bot">'. '<a href="' . Mage::getUrl($sub_cat->getUrlPath()). '"><span>' .$sub_category->getName() . "</span></a>\n";if($sub_category->hasChildren()) { $children1 = Mage::getModel('catalog/category')->getCategories($sub_category->getId()); $array .= Mage::helper('function')->get_sub_categories($children1, $sub_cat->getUrlPath()); }$array .= '</li>';}if($parent == 'Mobile Skin'){$array .= '<li class="mega-menu-column over-model"><a href="'.Mage::getBaseUrl().'shop/mobile-skin.html/" class="marTOP20pxCss"><span class="test-bottom">See over 200 models</li></span></a>';}if($parent == 'Mobile Case'){$array .= '<li class="mega-menu-column over-model"><a href="'.Mage::getBaseUrl().'shop/mobile-case.html/"><span class="test-bottom">See over 85 models</li></span></a>';}

if($parent == 'Mobile Case'){$array .= '</div><div class="col-md-3 mega-menu-subRight"> <div class="dropmenu-label">By Style</div> <div class="mega-menu-subRight-left mobileCaseByTypeMenuCss" id="mobileCaseMenuCssByType1">


<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=189"><span>3D</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=41"><span>Animals</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=193"><span>Desi</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=38"><span>Famous</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=67"><span>Fashion</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=333"><span>Flags</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=109"><span>Floral</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=191"><span>For-A-Cause</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=194"><span>Graphic</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=111"><span>Hearts</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=37"><span>Humour</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=190"><span>Illustration</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=113"><span>Jerseys</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=225"><span>Music</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=192"><span>Party</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=195"><span>Political</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=224"><span>Quirky</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=196"><span>Retro</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=56"><span>Sports</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=57"><span>Typography</span></a></li>
</div> <div class="mega-menu-subRight-right mobileCaseByTypeMenuCss" id="mobileCaseMenuCssByType2">
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=336"><span>Weapon</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=53"><span>Zodiac</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=197"><span>Auto & Transportation</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=48"><span>Cities & Travel</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=58"><span>Comic & Cartoon</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=335"><span>Gym & Health</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=200"><span>Movies & Television</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=199"><span>Nature & Landscape</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=334"><span>Profession & Personality</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=198"><span>Religion & Spirituality</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-case/apple/iphone-6.html/?case_style=115"><span>Textures & Patterns</span></a></li>

</div> <span style="clear:both;color:#000" onclick="jQuery( \'#mobileCaseMenuCssByType2\' ).removeClass( \'mobileCaseByTypeMenuCss\' );jQuery( \'#mobileCaseMenuCssByType1\' ).removeClass( \'mobileCaseByTypeMenuCss\' );jQuery(this).hide();"><a href="javascript:void(0)"><span class="test-bottom3">See All</span></a></span> </div> ';}

if($parent == 'Mobile Skin'){$array .= '</div><div class="col-md-3 mega-menu-subRight mega-menu-accordion"><div class="dropmenu-label">By Type</div><span class="hdg_btype"><h2>SKINS BY GENRE
</h2></span><div id="accordion-skin" class="panel-group"><div class="dropmenu-skin-label panel panel-default"><div class="panel-heading"> <span class="panel-title"><a href="#design-skin" data-toggle="collapse" data-parent="#accordion-skin">Designer Skins by Genre</a></span></div><div id="design-skin" class="panel-collapse collapse in"> <div class="panel-body">
<ul class="sub-drop-level"> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=189"><span><i class="fa fa-circle"></i>3D</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=41"><span><i class="fa fa-circle"></i>Animals</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=197"><span><i class="fa fa-circle"></i>Auto & Transportation</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=48"><span><i class="fa fa-circle"></i>Cities & Travel</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=58"><span><i class="fa fa-circle"></i>Comic & Cartoon</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=193"><span><i class="fa fa-circle"></i>Desi</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=38"><span><i class="fa fa-circle"></i>Famous</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=67"><span><i class="fa fa-circle"></i>Fashion</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=333"><span><i class="fa fa-circle"></i>Flags</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=109"><span><i class="fa fa-circle"></i>Floral</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=191"><span><i class="fa fa-circle"></i>For-A-Cause</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=194"><span><i class="fa fa-circle"></i>Graphic</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=335"><span><i class="fa fa-circle"></i>Gym & Health</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=111"><span><i class="fa fa-circle"></i>Hearts</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=37"><span><i class="fa fa-circle"></i>Humour</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=190"><span><i class="fa fa-circle"></i>Illustration</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=113"><span><i class="fa fa-circle"></i>Jerseys</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=200"><span><i class="fa fa-circle"></i>Movies & Television</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=225"><span><i class="fa fa-circle"></i>Music</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=199"><span><i class="fa fa-circle"></i>Nature & Landscape</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=192"><span><i class="fa fa-circle"></i>Party</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=195"><span><i class="fa fa-circle"></i>Political</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=334"><span><i class="fa fa-circle"></i>Profession & Personality</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=224"><span><i class="fa fa-circle"></i>Quirky</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=198"><span><i class="fa fa-circle"></i>Religion & Spirituality</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=196"><span><i class="fa fa-circle"></i>Retro</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=56"><span><i class="fa fa-circle"></i>Sports</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=115"><span><i class="fa fa-circle"></i>Textures & Patterns</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=57"><span><i class="fa fa-circle"></i>Typography</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html/?case_style=336"><span><i class="fa fa-circle"></i>Weapon</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html/?case_style=53"><span><i class="fa fa-circle"></i>Zodiac</span></a></li></ul> </div></div></div><span class="hdg_btype">
<br/>

<h2>TEXTURED SKINS</h2></span><div class="dropmenu-skin-label panel panel-default"><div class="panel-heading"> <span class="panel-title"><a href="#metallic-skin" data-toggle="collapse" data-parent="#accordion">Metallic Skins</a></span></div>

<div id="metallic-skin" class="panel-collapse collapse"> <div class="panel-body"><ul class="sub-drop-level"> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201460"><span><i class="fa fa-circle"></i>Shiny Golden</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201461"><span><i class="fa fa-circle"></i>Shiny Silver</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201462"><span><i class="fa fa-circle"></i>Silver wire drawing
</span></a></li> <li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201459"><span><i class="fa fa-circle"></i>Golden wire drawing
</span></a></li> <li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201458"><span><i class="fa fa-circle"></i>Dark grey wire drawing
</span></a></li> </ul> </div></div></div><div class="dropmenu-skin-label panel panel-default"><div class="panel-heading"> <span class="panel-title"><a href="#carbon-fiber-skin" data-toggle="collapse" data-parent="#accordion">Carbon Fiber skin</a></span></div><div id="carbon-fiber-skin" class="panel-collapse collapse"> <div class="panel-body"><ul class="sub-drop-level"> <li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201443"><span><i class="fa fa-circle"></i>Green carbon fiber

</span></a></li> <li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201446"><span><i class="fa fa-circle"></i>Purple carbon fiber

</span></a></li> <li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201444"><span><i class="fa fa-circle"></i>Orange carbon fiber
</span></a></li> <li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201445"><span><i class="fa fa-circle"></i>Pink carbon fiber

</span></a></li><li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201440"><span><i class="fa fa-circle"></i>Black carbon fiber


</span></a></li><li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201448"><span><i class="fa fa-circle"></i>Silver carbon fiber



</span></a></li><li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201441"><span><i class="fa fa-circle"></i>Copper carbon fiber



</span></a></li><li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201442"><span><i class="fa fa-circle"></i>Golden carbon fiber



</span></a></li><li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201449"><span><i class="fa fa-circle"></i>White carbon fiber




</span></a></li><li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201447"><span><i class="fa fa-circle"></i>Red carbon fiber


</span></a></li>
</ul> </div></div></div><div class="dropmenu-skin-label panel panel-default"><div class="panel-heading"> <span class="panel-title"><a href="#super-shining-skin" data-toggle="collapse" data-parent="#accordion">Super Shining Skins</a></span></div><div id="super-shining-skin" class="panel-collapse collapse"> <div class="panel-body"><ul class="sub-drop-level"> 


<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201473"><span><i class="fa fa-circle"></i>Red color shining
</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201471"><span><i class="fa fa-circle"></i>Orange color shining
</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201468"><span><i class="fa fa-circle"></i>Light blue color shining
</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201447"><span><i class="fa fa-circle"></i>Yellow color shining
</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201470"><span><i class="fa fa-circle"></i>Green color shining
</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201474"><span><i class="fa fa-circle"></i>White color shining
</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201472"><span><i class="fa fa-circle"></i>Light pink color shining

</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201463"><span><i class="fa fa-circle"></i>Black color shining

</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201465"><span><i class="fa fa-circle"></i>black big polygon shining

</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201467"><span><i class="fa fa-circle"></i>black small polygon shining

</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201464"><span><i class="fa fa-circle"></i>black abstract shining


</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201466"><span><i class="fa fa-circle"></i>black floral shining


</span></a></li> </ul> </div></div></div> <div class="dropmenu-skin-label panel panel-default"><div class="panel-heading"> <span class="panel-title"><a href="#3d-water-drop-skin" data-toggle="collapse" data-parent="#accordion">3D Water Drop Skins</a></span></div>
<div id="3d-water-drop-skin" class="panel-collapse collapse"> <div class="panel-body"><ul class="sub-drop-level"> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201434"><span><i class="fa fa-circle"></i>Black water drops
</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201437"><span><i class="fa fa-circle"></i>Pink water drops
</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201438"><span><i class="fa fa-circle"></i>Purple water drops
</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201439"><span><i class="fa fa-circle"></i>White water drops
</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201435"><span><i class="fa fa-circle"></i>Green water drops
</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201436"><span><i class="fa fa-circle"></i>Orange water drops
</span></a></li>
</ul> </div></div></div>



<div class="dropmenu-skin-label panel panel-default"><div class="panel-heading"> <span class="panel-title"><a href="#faux-leather-skin" data-toggle="collapse" data-parent="#accordion">Faux Leather Skins</a></span></div><div id="faux-leather-skin" class="panel-collapse collapse"> <div class="panel-body"><ul class="sub-drop-level"> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201451"><span><i class="fa fa-circle"></i>Black leather texture
</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201452"><span><i class="fa fa-circle"></i>Black snake texture
</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201455"><span><i class="fa fa-circle"></i>Grey snake texture
</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201453"><span><i class="fa fa-circle"></i>Brown snake texture
</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201457"><span><i class="fa fa-circle"></i>White snake texture
</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201450"><span><i class="fa fa-circle"></i>Black crocodile texture
</span></a></li>

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201456"><span><i class="fa fa-circle"></i>White crocodile texture
</span></a></li>

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/mobile-skin/apple/iphone-6.html?texturedPrdStr=201454"><span><i class="fa fa-circle"></i>Golden crocodile texture
</span></a></li></ul> </div></div></div> 
<div class="dropmenu-skin-label panel panel-default" style="display:none;"><div class="panel-heading"> <span class="panel-title"><a href="#wooden-skin" data-toggle="collapse" data-parent="#accordion">Wooden Skin</a></span></div><div id="wooden-skin" class="panel-collapse collapse"> <div class="panel-body"><ul class="sub-drop-level">
 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'"><span><i class="fa fa-circle"></i>Mohogany Texture</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'"><span><i class="fa fa-circle"></i>Rose Wood Texture</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'"><span><i class="fa fa-circle"></i>Padauk Texture</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'"><span><i class="fa fa-circle"></i>Eagle Wood Texture</span></a></li>

</ul> </div></div></div><div class="dropmenu-skin-label panel panel-default" style="display:none;"><div class="panel-heading"> <span class="panel-title"><a href="#pop-colour-skin" data-toggle="collapse" data-parent="#accordion">Pop Colour Skins</a></span></div><div id="pop-colour-skin" class="panel-collapse collapse"> <div class="panel-body"><ul class="sub-drop-level"> <li class="mega-menu-column"><a href="javascript:void(0);"><span><i class="fa fa-circle"></i>Red</span></a></li> <li class="mega-menu-column"><a href="javascript:void(0);"><span><i class="fa fa-circle"></i>Orange</span></a></li> <li class="mega-menu-column"><a href="javascript:void(0);"><span><i class="fa fa-circle"></i>Green</span></a></li> <li class="mega-menu-column"><a href="javascript:void(0);"><span><i class="fa fa-circle"></i>Yellow</span></a></li> <li class="mega-menu-column"><a href="javascript:void(0);"><span><i class="fa fa-circle"></i>White</span></a></li> <li class="mega-menu-column"><a href="javascript:void(0);"><span><i class="fa fa-circle"></i>Pink</span></a></li><li class="mega-menu-column"><a href="javascript:void(0);"><span><i class="fa fa-circle"></i>Purple</span></a></li><li class="mega-menu-column"><a href="javascript:void(0);"><span><i class="fa fa-circle"></i>Black</span></a></li><li class="mega-menu-column"><a href="javascript:void(0);"><span><i class="fa fa-circle"></i>Blue</span></a></li> </ul> </div></div></div></div> </div> ';}$array .= '</ul>'; 
return$array ;
}

function get_sub_categories($sub_categories1, $cat_url) {
  $array= '<ul class="sub-cat mega-menu">';
  $counter = 0;
  foreach($sub_categories1 as $sub_category1) {
	$sub_cat1 = Mage::getModel('catalog/category')->load($sub_category1->getId());
	$array .= '<li class="mega-menu-column">'.
	 '<a href="' . $sub_cat1->getUrl(). '"><span>' .$sub_category1->getName() . "</span></a>\n";	 
	 if($sub_category1->hasChildren()) {
		 $children2 = Mage::getModel('catalog/category')->getCategories($sub_category1->getId());
	  	 $array .= Mage::helper('function')->get_sub_categories($children2);
	 }		 
    $array .= '</li>';
	$counter++;
	if($counter == 4)
	{
		$array .= '<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().$cat_url.'"><span class="red">See All &raquo;</span></a>';
		break;}
  }
  return  $array . '</ul>';
}

function get_dropdown($drop_cat_name) {	 	// only for laptop 
		return '<ul class="dropdown-menu mega-menu lapi-tab"><div class="col-md-9 mega-menu-subLeft case"><div class="dropmenu-label">By Device/Model</div><li class="mega-menu-column"><img src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN).'frontend/rwd/default/images/outline-laptop.png"> <a href="#"><span class="more_case">Laptop (By Size)</span></a>
		<ul class="sub-cat mega-menu">
		<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/"><span>11 Inch/ 12 Inch </span></a></li> 
		<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/13inch-14inch.html/"><span>13 Inch/ 14 Inch</span></a></li> 
		<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/15inch-16inch.html/"><span>15 Inch / 16 Inch</span></a></li> 
		<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/17-inch-18-inch.html/"><span>17Inch / 18 Inch</span></a></li>
		<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/custom-size.html/"><span>Custom Size</span></a></li>
		
		</ul> </li> 
		
		<li class="mega-menu-column">
		 <img src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN).'frontend/rwd/default/images/Ipad-2.png"> <a href="#"><span class="more_case">Apple Ipads (By Model)</span></a>
		 <ul class="sub-cat mega-menu">
		 <!--<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/tablet-skin/apple/ipad.html/"><span>iPad</span></a></li> -->
		 <li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/tablet-skin/apple/ipad-2.html/"><span>iPad 2</span></a></li>
		 <li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/tablet-skin/apple/ipad-3.html/"><span>iPad 3</span></a></li>	 
		 <li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/tablet-skin/apple/ipad-4.html/"><span>iPad 4</span></a></li>
		 <li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/tablet-skin/apple/ipad-air.html/"><span>iPad Air</span></a></li>
		 <li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/tablet-skin/apple/ipad-air-2.html/"><span>iPad Air 2</span></a></li>		  
		 <li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/tablet-skin/apple/ipad-mini-retina.html/"><span>iPad Mini/Retina</span></a></li> 		  
		 <li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/tablet-skin/apple/ipad-2.html/"><span>iPad Mini 2</span></a></li> 
		 </ul> </li> 
		
		
		<li class="mega-menu-column"><img src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN).'frontend/rwd/default/images/sam-10inch.jpg"> <a href="#"><span class="more_case">Samsung (By Model)</span></a>
		<ul class="sub-cat mega-menu">
		<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/tablet-skin/samsung/galaxy-tab-2.html"><span>Galaxy Tab 2</span></a></li> 
		<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/tablet-skin/samsung/galaxy-tab3-7-0.html"><span>Galaxy Tab3 7.0</span></a></li> 
		<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/tablet-skin/samsung/galaxy-tab3-8-0.html"><span>Galaxy Tab3 8.0 </span></a></li> 
		<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/tablet-skin/samsung/galaxy-tab3-10-1.html"><span>Galaxy Tab3 10.1 </span></a></li> 
		<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/tablet-skin/samsung/galaxy-tab-7-0-plus.html"><span>Galaxy Tab 7.0 Plus</span></a></li> 
		<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/tablet-skin/samsung/galaxy-tab-7-7.html"><span>Galaxy Tab 7.7</span></a></li> 		
		</ul> </li> 
		
		
		<li class="mega-menu-column"><img src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN).'frontend/rwd/default/images/outline-tab.png"> <a href="#"><span class="more_case">All Other Tabs (By Size)</span></a><ul class="sub-cat mega-menu">
		<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/tablet-skin/all-tabs.html/"><span>7 Inch / 8 Inch</span></a></li> 
		<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/tablet-skin/all-tabs.html/"><span>9 Inch / 10 Inch</span></a></li> 
		<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/tablet-skin/custom-size.html/"><span>Custom Size</span></a></li>
		
		</ul> </li></div>
		<div class="col-md-3 mega-menu-subRight mega-menu-accordion"><div class="dropmenu-label">By Type</div><span class="hdg_btype"><h2>SKINS BY GENRE
</h2></span><div id="accordion-skin" class="panel-group"><div class="dropmenu-skin-label panel panel-default"><div class="panel-heading"> <span class="panel-title"><a href="#design-skins" data-toggle="collapse" data-parent="#accordion-skin">Designer Skins by Genre</a></span></div><div id="design-skins" class="panel-collapse collapse in"> <div class="panel-body">
<ul class="sub-drop-level"> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html//?case_style=189"><span><i class="fa fa-circle"></i>3D</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=41"><span><i class="fa fa-circle"></i>Animals</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=197"><span><i class="fa fa-circle"></i>Auto & Transportation</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=48"><span><i class="fa fa-circle"></i>Cities & Travel</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=58"><span><i class="fa fa-circle"></i>Comic & Cartoon</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=193"><span><i class="fa fa-circle"></i>Desi</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=38"><span><i class="fa fa-circle"></i>Famous</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=67"><span><i class="fa fa-circle"></i>Fashion</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=333"><span><i class="fa fa-circle"></i>Flags</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html/?case_style=109"><span><i class="fa fa-circle"></i>Floral</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=191"><span><i class="fa fa-circle"></i>For-A-Cause</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=194"><span><i class="fa fa-circle"></i>Graphic</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=335"><span><i class="fa fa-circle"></i>Gym & Health</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=111"><span><i class="fa fa-circle"></i>Hearts</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=37"><span><i class="fa fa-circle"></i>Humour</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=190"><span><i class="fa fa-circle"></i>Illustration</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=113"><span><i class="fa fa-circle"></i>Jerseys</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=200"><span><i class="fa fa-circle"></i>Movies & Television</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=225"><span><i class="fa fa-circle"></i>Music</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=199"><span><i class="fa fa-circle"></i>Nature & Landscape</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=192"><span><i class="fa fa-circle"></i>Party</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=195"><span><i class="fa fa-circle"></i>Political</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=334"><span><i class="fa fa-circle"></i>Profession & Personality</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=224"><span><i class="fa fa-circle"></i>Quirky</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=198"><span><i class="fa fa-circle"></i>Religion & Spirituality</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=196"><span><i class="fa fa-circle"></i>Retro</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=56"><span><i class="fa fa-circle"></i>Sports</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=115"><span><i class="fa fa-circle"></i>Textures & Patterns</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=57"><span><i class="fa fa-circle"></i>Typography</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=336"><span><i class="fa fa-circle"></i>Weapon</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop/laptop-skin/11inch-12inch.html/?case_style=53"><span><i class="fa fa-circle"></i>Zodiac</span></a></li></ul> </div></div></div><span class="hdg_btype"><br/><h2>TEXTURED SKINS</h2></span><div class="dropmenu-skin-label panel panel-default"><div class="panel-heading"> <span class="panel-title"><a href="#metallic-skins" data-toggle="collapse" data-parent="#accordion">Metallic Skins</a></span></div>

<div id="metallic-skins" class="panel-collapse collapse"> <div class="panel-body"><ul class="sub-drop-level"> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201612,201653&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Shiny Golden</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201613,201654&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Shiny Silver</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201614,201655&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Silver wire drawing
</span></a></li> <li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201611,201652&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Golden wire drawing
</span></a></li> <li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201610,201651&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Dark grey wire drawing
</span></a></li> </ul> </div></div></div><div class="dropmenu-skin-label panel panel-default"><div class="panel-heading"> <span class="panel-title"><a href="#carbon-fiber-skins" data-toggle="collapse" data-parent="#accordion">Carbon Fiber skin</a></span></div><div id="carbon-fiber-skins" class="panel-collapse collapse"> <div class="panel-body"><ul class="sub-drop-level"> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201595,201636&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Green carbon fiber

</span></a></li> <li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201598,201639&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Purple carbon fiber

</span></a></li> <li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201596,201637&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Orange carbon fiber
</span></a></li> <li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201597,201638&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Pink carbon fiber

</span></a></li><li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201592,201633&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Black carbon fiber


</span></a></li><li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201600,201641&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Silver carbon fiber



</span></a></li><li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201593,201634&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Copper carbon fiber



</span></a></li><li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201594,201635&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Golden carbon fiber



</span></a></li><li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201601,201642&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>White carbon fiber




</span></a></li><li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201599,201640&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Red carbon fiber


</span></a></li>
</ul> </div></div></div><div class="dropmenu-skin-label panel panel-default"><div class="panel-heading"> <span class="panel-title"><a href="#super-shining-skins" data-toggle="collapse" data-parent="#accordion">Super Shining Skins</a></span></div><div id="super-shining-skins" class="panel-collapse collapse"> <div class="panel-body"><ul class="sub-drop-level"> 


<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201625,201666&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Red color shining
</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201623,201664&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Orange color shining
</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201620,201661&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Light blue color shining
</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201447&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Yellow color shining
</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201622,201663&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Green color shining
</span></a></li> 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201626,201667&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>White color shining
</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201624,201665&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Light pink color shining

</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201615,201656&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Black color shining

</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201617,201658&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>black big polygon shining

</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201619,201660&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>black small polygon shining

</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201616,201657&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>black abstract shining


</span></a></li>
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201618,201659&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>black floral shining


</span></a></li> </ul> </div></div></div> <div class="dropmenu-skin-label panel panel-default"><div class="panel-heading"> <span class="panel-title"><a href="#3d-water-drop-skins" data-toggle="collapse" data-parent="#accordion">3D Water Drop Skins</a></span></div>
<div id="3d-water-drop-skins" class="panel-collapse collapse"> <div class="panel-body"><ul class="sub-drop-level"> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201586,201627&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Black water drops
</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201589,201630&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Pink water drops
</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201590,201631&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Purple water drops
</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201591,201632&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>White water drops
</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201587,201628&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Green water drops
</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201588,201629&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Orange water drops
</span></a></li> </ul> </div></div></div>
<div class="dropmenu-skin-label panel panel-default"><div class="panel-heading"> <span class="panel-title"><a href="#faux-leather-skins" data-toggle="collapse" data-parent="#accordion">Faux Leather Skins</a></span></div><div id="faux-leather-skins" class="panel-collapse collapse"> <div class="panel-body"><ul class="sub-drop-level"> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201603,201644&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Black leather texture</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201604,201645&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Black snake texture</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201607,201648&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Grey snake texture</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201605,201646&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Brown snake texture</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201609,201650&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>White snake texture</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201602,201643&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Black crocodile texture</span></a></li>

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201608,201649&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>White crocodile texture</span></a></li>

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'shop.html?texturedPrdStr=201606,201647&laptabtexturedskin=yes"><span><i class="fa fa-circle"></i>Golden crocodile texture</span></a></li></ul> </div></div></div> 
<div class="dropmenu-skin-label panel panel-default" style="display:none;"><div class="panel-heading"> <span class="panel-title"><a href="#wooden-skins" data-toggle="collapse" data-parent="#accordion">Wooden Skin</a></span></div><div id="wooden-skins" class="panel-collapse collapse"> <div class="panel-body"><ul class="sub-drop-level">
 
<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'"><span><i class="fa fa-circle"></i>Mohogany Texture</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'"><span><i class="fa fa-circle"></i>Rose Wood Texture</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'"><span><i class="fa fa-circle"></i>Padauk Texture</span></a></li> 

<li class="mega-menu-column"><a href="'.Mage::getBaseUrl().'"><span><i class="fa fa-circle"></i>Eagle Wood Texture</span></a></li>

</ul> </div></div></div><div class="dropmenu-skin-label panel panel-default" style="display:none;"><div class="panel-heading"> <span class="panel-title"><a href="#pop-colour-skins" data-toggle="collapse" data-parent="#accordion">Pop Colour Skins</a></span></div><div id="pop-colour-skins" class="panel-collapse collapse"> <div class="panel-body"><ul class="sub-drop-level"> <li class="mega-menu-column"><a href="javascript:void(0);"><span><i class="fa fa-circle"></i>Red</span></a></li> <li class="mega-menu-column"><a href="javascript:void(0);"><span><i class="fa fa-circle"></i>Orange</span></a></li> <li class="mega-menu-column"><a href="javascript:void(0);"><span><i class="fa fa-circle"></i>Green</span></a></li> <li class="mega-menu-column"><a href="javascript:void(0);"><span><i class="fa fa-circle"></i>Yellow</span></a></li> <li class="mega-menu-column"><a href="javascript:void(0);"><span><i class="fa fa-circle"></i>White</span></a></li> <li class="mega-menu-column"><a href="javascript:void(0);"><span><i class="fa fa-circle"></i>Pink</span></a></li><li class="mega-menu-column"><a href="javascript:void(0);"><span><i class="fa fa-circle"></i>Purple</span></a></li><li class="mega-menu-column"><a href="javascript:void(0);"><span><i class="fa fa-circle"></i>Black</span></a></li><li class="mega-menu-column"><a href="javascript:void(0);"><span><i class="fa fa-circle"></i>Blue</span></a></li> </ul> </div></div></div></div> </div>';
}
function get_dropdown1($drop_cat_name) {	// only for tshirt 	
 return '<ul class="dropdown-menu mega-menu lapi tees-tab"> <div class="col-md-8 mega-menu-subLeft case"><div class="dropmenu-label">By Gender</div><li class="mega-menu-column lapi-brand"> <div class="chooseBrand"><div class="bselect">  <ul class="tees-size"><li> <a href="'.Mage::getBaseUrl().'shop/t-shirt/for-him.html/"><img src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN).'frontend/rwd/default/images/create-t-shirt-man-tag.png"> <span>Guys Cotton Tees</span> </a></li><li> <a href="'.Mage::getBaseUrl().'shop/t-shirt/for-her.html/"><img src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN).'frontend/rwd/default/images/create-t-shirt-women-tag.png"> <span>Girls Cotton Tees</span> </a></li><!--<li> <a href="'.Mage::getBaseUrl().'shop/t-shirt/for-kids-teens.html/"><img src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN).'frontend/rwd/default/images/create-t-shirt-kid-tag.png"> <span>Kids/Teen Cotton Tees </span> </a></li>-->  </ul></div> </div></li> </div> 
 
 <div class="col-md-4 mega-menu-subRight"><div class="dropmenu-label">By Style</div> 
 
 <div class="mega-menu-subRight-left tshirtByTypeMenuCss" id="tshirtMenuCssByType1">
 
 

 		<li class="mega-menu-column">
   <a  title="3D" href="'.Mage::getBaseUrl().'shop/t-shirt/for-him.html?case_style=189"><span>3D</span></a>
</li>
  		<li class="mega-menu-column">
   <a  title="Animals" href="'.Mage::getBaseUrl().'shop/t-shirt/for-him.html?case_style=41"><span>Animals</span></a>
</li>
  		<li class="mega-menu-column">
   <a  title="Auto &amp; Transportation" href="'.Mage::getBaseUrl().'shop/t-shirt/for-him.html?case_style=331"><span>Auto &amp; Transportation</span></a>
</li>
  		<li class="mega-menu-column">
   <a  title="Comic &amp; Cartoon" href="'.Mage::getBaseUrl().'shop/t-shirt/for-him.html?case_style=58"><span>Comic &amp; Cartoon</span></a>
</li>
  		<li class="mega-menu-column">
   <a  title="Desi" href="'.Mage::getBaseUrl().'shop/t-shirt/for-him.html?case_style=193"><span>Desi</span></a>
</li>
  		<li class="mega-menu-column">
   <a  title="Famous" href="'.Mage::getBaseUrl().'shop/t-shirt/for-him.html?case_style=38"><span>Famous</span></a>
</li>
  		<li class="mega-menu-column">
   <a  title="Fashion" href="'.Mage::getBaseUrl().'shop/t-shirt/for-him.html?case_style=67"><span>Fashion</span></a>
</li>
  		<li class="mega-menu-column">
   <a  title="Graphic" href="'.Mage::getBaseUrl().'shop/t-shirt/for-him.html?case_style=227"><span>Graphic</span></a>
</li>
  		<li class="mega-menu-column">
   <a  title="Gym &amp; Health" href="'.Mage::getBaseUrl().'shop/t-shirt/for-him.html?case_style=335"><span>Gym &amp; Health</span></a>
</li>


  		<li class="mega-menu-column">
   <a  title="Humour" href="'.Mage::getBaseUrl().'shop/t-shirt/for-him.html?case_style=332"><span>Humour</span></a>
</li>

</div> <div class="mega-menu-subRight-right tshirtByTypeMenuCss" id="tshirtMenuCssByType2">

  		<li class="mega-menu-column">
   <a  title="Movies &amp; Television" href="'.Mage::getBaseUrl().'shop/t-shirt/for-him.html?case_style=200"><span>Movies &amp; Television</span></a>
</li>
  		<li class="mega-menu-column">
   <a  title="Music" href="'.Mage::getBaseUrl().'shop/t-shirt/for-him.html?case_style=225"><span>Music</span></a>
</li>
  		<li class="mega-menu-column">
   <a  title="Party" href="'.Mage::getBaseUrl().'shop/t-shirt/for-him.html?case_style=192"><span>Party</span></a>
</li>
  		<li class="mega-menu-column">
   <a  title="Profession &amp; Personality" href="'.Mage::getBaseUrl().'shop/t-shirt/for-him.html?case_style=334"><span>Profession &amp; Personality</span></a>
</li>
  		<li class="mega-menu-column">
   <a  title="Quirky" href="'.Mage::getBaseUrl().'shop/t-shirt/for-him.html?case_style=224"><span>Quirky</span></a>
</li>
  		<li class="mega-menu-column">
   <a  title="Retro" href="'.Mage::getBaseUrl().'shop/t-shirt/for-him.html?case_style=196"><span>Retro</span></a>
</li>
  		<li class="mega-menu-column">
   <a  title="Sports" href="'.Mage::getBaseUrl().'shop/t-shirt/for-him.html?case_style=56"><span>Sports</span></a>
</li>
  		<li class="mega-menu-column">
   <a  title="Typography" href="'.Mage::getBaseUrl().'shop/t-shirt/for-him.html?case_style=57"><span>Typography</span></a>
</li>
	   
	   
  		<li class="mega-menu-column">
   <a  title="Weapon" href="'.Mage::getBaseUrl().'shop/t-shirt/for-him.html?case_style=336"><span>Weapon</span></a>
</li>
     
     
 
 
 </div> </div> 
 
 
 </ul>';
     // end tshirt skin 
	 
	/*echo '<!--<span style="clear:both;color:#000" onclick="jQuery( \'#tshirtMenuCssByType2\' ).removeClass( \'tshirtByTypeMenuCss\' );jQuery( \'#tshirtMenuCssByType1\' ).removeClass( \'tshirtByTypeMenuCss\' );jQuery(this).hide();"><a href="javascript:void(0)"><span class="test-bottom2">See All</span></a></span>-->';*/ 
}


		
////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getRandomMobileSkinBlankImage($mobile_skin_cat_url_key)
 {
	 
				//$mobile_case_cat_arr[$get_random_cat];
				
    $folderURL = Mage::helper('function')->graGetFolderUrl('mobile-skin/'.$mobile_skin_cat_url_key); 
    $folderPath = Mage::helper('function')->graGetFolderPath('mobile-skin/'.$mobile_skin_cat_url_key);

	 
  
  if(!is_file($folderPath . DS . 'resized245' . DS . 'mask-back.png'))
   $blank_backImagePath = Mage::helper('function')->resizeImg('mask-back.png', 245, 245,$folderURL,$folderPath,'resized245');	   
  else 	
   $blank_backImagePath = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL).'media/blank_images/'.'mobile-skin/'.$mobile_skin_cat_url_key.'/'.$graBlankImageFolderKey.'/resized245/mask-back.png'; 
 
    return $blank_backImagePath;
 
 }
 
function getRandomMobileCaseBlankImage($mobile_case_cat_url_key)
 {				 
				//$mobile_case_cat_arr_url_key[$get_random_cat];
				
    $folderURL = Mage::helper('function')->graGetFolderUrl('mobile-case/'.$mobile_case_cat_url_key); 
    $folderPath = Mage::helper('function')->graGetFolderPath('mobile-case/'.$mobile_case_cat_url_key);

	 
  
  if(!is_file($folderPath . DS . 'resized245' . DS . 'mask.png'))
   $blank_backImagePath = Mage::helper('function')->resizeImg('mask.png', 245, 245,$folderURL,$folderPath,'resized245');	   
  else 	
   $blank_backImagePath = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL).'media/blank_images/'.'mobile-case/'.$mobile_case_cat_url_key.'/'.$graBlankImageFolderKey.'/resized245/mask.png';	
 
    return $blank_backImagePath;
 
 }
 
function getNumberOfObjectsType($prd_id,$objType)
 {				 
			$_product = Mage::getModel("catalog/product")->load($prd_id); 

 //echo $_product->getBhsTplCustSrlz();	
 
			$unserialize_arr = json_decode( $_product->getBhsTplCustSrlz() );
			$total_obj = array();
			$total_obj_trk = 0;
			
			
		 
			
				 foreach($unserialize_arr as $unserialize_arr_val_arr)
				  {
					foreach($unserialize_arr_val_arr as $unserialize_arr_key=>$unserialize_arr_val){
						if($unserialize_arr_val->type==$objType){
						
						//echo $unserialize_arr_key;
						 
							//$total_obj[$total_obj_trk] = $unserialize_arr_key;
							$total_obj[$total_obj_trk] = $total_obj_trk;
							$total_obj_trk++;
						} 
					}
				  }	


				  
	return $total_obj;
 }
 

function getCategoryPathByName($categoryId,$skip=0,$obj=false,$path=false)
 {
   if($path == false){
		if($obj==false)
		$category = Mage::getModel('catalog/category')->load($categoryId);
		else
		$category = Mage::getModel('catalog/category')->load($categoryId->getId());
		
		$coll = $category->getResourceCollection();
		$pathIds = $category->getPathIds();
		$coll->addAttributeToSelect('name');
		$coll->addAttributeToFilter('entity_id', array('in' => $pathIds));
		$coll->addOrderField('level');
	}
	else	
	{
		$coll = $path;
	}
		$result = '';
		
		$counter = 0;
		
		foreach ($coll as $cat) {
		
					if($counter < $skip)
					{
					$counter++;
					continue;
					} 		
		
		  if($path != false){
			$categoryPath = Mage::getModel('catalog/category')->load($cat);
			$result .= $categoryPath->getName().'/';
		  }	
		  else
			$result .= $cat->getName().'/';
		} 
		
		
		return $result;
 }
 public function getCurrentUrl()
 {
		 $pageURL = 'http';
		 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
		 return $pageURL; 
 }
 
 
 public function generateUniqueId($maxLength = null) {
    $entropy = '';

    // try ssl first
    if (function_exists('openssl_random_pseudo_bytes')) {
        $entropy = openssl_random_pseudo_bytes(64, $strong);
        // skip ssl since it wasn't using the strong algo
        if($strong !== true) {
            $entropy = '';
        }
    }

    // add some basic mt_rand/uniqid combo
    $entropy .= uniqid(mt_rand(), true);

    // try to read from the windows RNG
    if (class_exists('COM')) {
        try {
            $com = new COM('CAPICOM.Utilities.1');
            $entropy .= base64_decode($com->GetRandom(64, 0));
        } catch (Exception $ex) {
        }
    }

    // try to read from the unix RNG
    if (is_readable('/dev/urandom')) {
        $h = fopen('/dev/urandom', 'rb');
        $entropy .= fread($h, 64);
        fclose($h);
    }

    $hash = hash('whirlpool', $entropy);
    if ($maxLength) {
        return substr($hash, 0, $maxLength);
    }
    return $hash;
} 
 
public function checkCustomerLoggedIn()
{
		Mage::getSingleton('core/session', array('name'=>'frontend'));

		$sessionCustomer =Mage::getSingleton("customer/session");
		if ($sessionCustomer->isLoggedIn()) {
		 return true;
		} else {
		  return false;
		}	
	
} 
 public function getCartItemTotalMainPrice()
{
	$cart = Mage::getModel('checkout/cart')->getQuote();
	$cartSubtotal = 0;
	
	foreach ($cart->getAllItems() as $item) {	
		if($item->getProductType() === 'configurable') 
			continue;
		$cartSubtotal += $item->getProduct()->getPrice() * $item->getQty();
	}
	return $cartSubtotal;	
} 

public function getCartItemsTotals()
{
	 $totals = Mage::getSingleton('checkout/session')->getQuote()->getTotals(); //Total object
	 return $totals;
} 

public function getCategoryRelatedInfo($productInfo)
{
	$skipCategoryLevel = array();
} 

public function mobileSkinBrandIncludeInNavigationBottom()
{
	$mobskinArr = array(154,155,156,304,305,306,307,308,852,846);
	return $mobskinArr;
}

public function mobileCaseBrandIncludeInNavigationBottom()
{
	$mobcaseArr = array(136,137,162,310,311,312,313,314,138,161);
	return $mobcaseArr;
}

 
}
?>