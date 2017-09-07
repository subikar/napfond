<?php
/**
 * GoMage Product Designer Extension
 *
 * @category     Extension
 * @copyright    Copyright (c) 2013 GoMage (http://www.gomage.com)
 * @author       GoMage
 * @license      http://www.gomage.com/license-agreement/  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use/
 * @version      Release: 1.0.0
 * @since        Available since Release 1.0.0
 */

/**
 * Designer controller
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage controllers
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_IndexController extends Mage_Core_Controller_Front_Action
{
    public function dispatch($action)
    {
        $moduleEnabled = Mage::getStoreConfig('gomage_designer/general/enabled', Mage::app()->getStore());
        if (!$moduleEnabled) {
            $action = 'noRoute';
        }
        parent::dispatch($action);
    }

    /**
     * Inititalize product
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function _initializeProduct()
    {
        return Mage::helper('gomage_designer')->initializeProduct();
    }

    /**
     * Index action
     *
     * @return void
     */
    public function indexAction()
    {
        $product = $this->_initializeProduct();
        if ($product->getId() && (!$product->getEnableProductDesigner() || !$product->hasImagesForDesign())
            && !Mage::helper('gomage_designer')->isNavigationEnabled()) {
            $this->_redirectReferer();
        } elseif (!$product->getId() && !Mage::helper('gomage_designer')->isNavigationEnabled()) {
            $this->_redirectReferer();
        }
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->_getTitle());
        $this->renderLayout();
    }

    /**
     * Change product action
     *
     * @return void
     */
    public function changeProductAction()
    {
        $request = $this->getRequest();
        $isAjax = (bool) $request->getParam('ajax');
        if (!$isAjax) {
            $this->norouteAction();
            return;
        }

        try {
            $product = $this->_initializeProduct();
            if (!$product || !$product->getId()) {
                throw new Exception(Mage::helper('gomage_designer')->__('Product with id %d not found',
                    $this->getRequest()->getParam('id')));
            }
            $settings = Mage::helper('gomage_designer')->getProductSettingForEditor($product);
            $responseData = array(
                'product_settings' => $settings,
                'design_price' => $this->_getDesignPriceHtml(),
                'price_config' => Mage::helper('gomage_designer')->getProductPriceConfig(),
            );
            if ($productColors = $product->getProductColors()) {
                $responseData['product_colors'] = $productColors;
            }
            Mage::helper('gomage_designer/ajax')->sendSuccess($responseData);
        } catch(Exception $e) {
            Mage::helper('gomage_designer/ajax')->sendError($e->getMessage());
        }
    }

    /**
     * Filter products action
     *
     * @return void
     */
    public function filterProductsAction()
    {
        $isAjax = (bool) $this->getRequest()->getPost('ajax');
        if (!$isAjax) {
            $this->norouteAction();
            return;
        }

        $this->loadLayout();
        $navigationBlock = $this->getLayout()->getBlock('productNavigator');
        $responseData = array(
            'navigation_filters'  => $navigationBlock->getFiltersHtml(),
            'navigation_prodcuts' => $navigationBlock->getProductListHtml()
        );

        Mage::helper('gomage_designer/ajax')->sendSuccess($responseData);
    }

    /**
     * Filter cliparts action
     *
     * @return void
     */
    public function filterClipartsAction()
    {
        $isAjax = (bool) $this->getRequest()->getPost('ajax');
        if (!$isAjax) {
            $this->norouteAction();
            return;
        }

        $this->loadLayout();
        $designBlock = $this->getLayout()->getBlock('design');
        $responseData = array(
            'filters' => $designBlock->getChildHtml('design.filters'),
            'cliparts' => $designBlock->getChildHtml('design.cliparts')
        );
		
		if($this->getRequest()->getPost('onlyClipArt') == 'yes')
		Mage::helper('gomage_designer/ajax')->sendSuccessGra($responseData['cliparts']);
		else
        Mage::helper('gomage_designer/ajax')->sendSuccess($responseData);
    }

 
    /*public function filterClipartsAction()
    {
        $isAjax = (bool) $this->getRequest()->getPost('ajax');
        if (!$isAjax) {
            $this->norouteAction();
            return;
        }

        $this->loadLayout();
        $designBlock = $this->getLayout()->getBlock('design');
        $responseData = array(
            'filters' => $designBlock->getChildHtml('design.filters'),
            'cliparts' => $designBlock->getChildHtml('design.cliparts')
        );
        Mage::helper('gomage_designer/ajax')->sendSuccess($responseData);
    }*/

 

    /**
     * Save product designed images and redirect to product view page
     *
     * @return void
     */
    public function continueAction()
    {
        /**
         * @var $request Mage_Core_Controller_Request_Http
         * @var $product Mage_Catalog_Model_Product
         */
        $request = $this->getRequest();
        $isAjax = $request->isAjax();
        if (!$isAjax) {
            $this->norouteAction();
            return;
        }
        try {
            $design = $this->_saveDesign();
			
			
		$connection = Mage::helper('function/more')->getDbReadObject();

		$connection_write = Mage::helper('function/more')->getDbWriteObject();
		
		
		$string_canvas_object_json = Mage::app()->getRequest()->getParam('string_canvas_object_json');
		
		//echo 'select id from gomage_saved_canvas_object where designid = "'.$design->getId().'"';
		
		$savedCanvasobjectId = $connection->fetchOne('select id from gomage_saved_canvas_object where designid = "'.$designId.'" and imageid="'.$imageId.'"');
		
		$query = '';
		
		
			
			
		
		$elementDetails = Mage::app()->getRequest()->getParam('elementDetails');
		
		$elementDetails = json_decode(stripslashes($elementDetails));
		
		
		foreach($elementDetails as $elementDetailsKEY=>$elementDetailsVAL){
			//echo '<pre>';
			//print_r($elementDetailsVAL);			
			//echo json_encode(($elementDetailsVAL));
			$connection_write->query("delete from gomage_saved_canvas_object where imageid='".$elementDetailsKEY."'"); 
			$query ='insert into gomage_saved_canvas_object set save_canvas_object="'.addslashes(json_encode(($elementDetailsVAL))).'",designid = "'.$design->getId().'",imageid="'.$elementDetailsKEY.'"';
			$connection_write->query($query);
		}
		
		$gravitaCustomSessionId =Mage::getSingleton('core/session')->getGravitaCustomSessionId();
		
		
		
		$connection_write->query('update gomage_productdesigner_design_image set gravita_custom_session_id="'.$gravitaCustomSessionId.'" where design_id = "'.$design->getId().'"'); 
		 
		 
		 
		$connection_write->query('update gomage_productdesigner_design set gravita_custom_session_id="'.$gravitaCustomSessionId.'" where design_id = "'.$design->getId().'"');
			
			
			
			
			if($design->getId())
			$this->gravita_custom_mobile_images($design);	
			
            $product = Mage::registry('product');
            Mage::helper('gomage_designer/ajax')->sendRedirect(array(
                'url' => $product->getDesignedProductUrl($design->getId()),
                'design_id' => $design->getId()
            ));
			Mage::getSingleton('core/session')->unsGravitaCustomSessionId();
        } catch (Exception $e) {
            Mage::helper('gomage_designer/ajax')->sendError($e->getMessage());
            Mage::logException($e);
        }
    }

    protected function _saveDesign()
    {
        return Mage::helper('gomage_designer')->saveProductDesignedImages();
    }

    public function uploadImagesAction()
    {
        $this->loadLayout();
        $block = $this->getLayout()->getBlock('uploadedImages');
        $baseMediaPath = Mage::getSingleton('gomage_designer/uploadedImage_config')->getBaseMediaPath();
        $allowedFormatsString = str_replace('/', ',', Mage::getStoreConfig('gomage_designer/upload_image/format'));
        $maxUploadFileSize = Mage::getStoreConfig('gomage_designer/upload_image/size');
        $allowedFormats = explode(',', $allowedFormatsString);
        $sessionId = $this->getSessionId();
        $customerId = (int) $this->_getCustomerSession()->getCustomerId();
        $uploadedFilesCount = 0;
        $errors = array();

        try {
            if (!isset($_FILES['filesToUpload'])) {
                throw new Exception(Mage::helper('gomage_designer')->__('Please, select files for upload'));
            }
            $files = $this->prepareFilesArray($_FILES['filesToUpload']);
            foreach ($files as $file) {
                if (!$file['name']) {
                    continue;
                }
                if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE
                    || $file['size'] > $maxUploadFileSize * 1024 * 1024) {
					$errors['size'] = Mage::helper('gomage_designer')->__('You can not upload files larger than %d MB', $maxUploadFileSize);					
                    continue;
                }

                $imageExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if (!in_array($imageExtension, $allowedFormats)) {
                    $errors['type'] = Mage::helper('gomage_designer')->__('Cannot upload the file. The format is not supported. Supported file formats are: %s', $allowedFormatsString);
                    continue;
                }

                $fileName = substr(sha1(microtime()), 0, 20) . Mage::helper('gomage_designer')->formatFileName($file['name']);
                $fileDir = '/' . ($customerId ? $customerId : $sessionId) . '/';
                $destinationDir = $baseMediaPath . $fileDir;
                if (!file_exists($destinationDir)) {
                    mkdir($destinationDir, 0777, true);
                }
                $destinationFile = $destinationDir . $fileName;

                if (move_uploaded_file($file['tmp_name'], $destinationFile)) {
					
					$gravitaCustomSessionId =Mage::getSingleton('core/session')->getGravitaCustomSessionId();
					
                    $imageData = array(
                        'image' => $fileDir . $fileName,
                        'customer_id' => $customerId,
                        'session_id' => $customerId ? $customerId : $sessionId,
						'gravita_custom_session_id' => $gravitaCustomSessionId
                    );
                    $uploadImage = Mage::getModel('gomage_designer/uploadedImage');
                    $uploadImage->setData($imageData);
                    /*$uploadImage->resizeImage(Mage::getSingleton('gomage_designer/uploadedImage_config')
                        ->getMediaPath($fileDir . $fileName));*/
                    $uploadImage->save();
                    $uploadedFilesCount++;
                }
            }

            if (!empty($errors)) {
                throw new Exception(implode("\n", $errors));
            }
            if ($uploadedFilesCount == 0) {
                throw new Exception(Mage::helper('gomage_designer')->__('Please, select files for upload'));
            }

        } catch (Exception $e) {
            $block->setError($e->getMessage());
        }

        $content = preg_replace('/\t+|\n+|\s{2,}/', '', $block->toHtml());
		
		if(is_object($e))
        $this->getResponse()->setBody(json_encode(array('status' => 'error','message' => $e->getMessage(), 'code' => 1337)));
		else{
			$content = preg_replace('/\t+|\n+|\s{2,}/', '', $block->toHtml());
			$this->getResponse()->setBody($content);			
		}
		
    }

    public function removeUploadedImagesAction()
    {
        $isAjax = (bool) $this->getRequest()->getPost('ajax');
        if (!$isAjax) {
            $this->norouteAction();
            return;
        }

        try {
            Mage::getModel('gomage_designer/uploadedImage')->removeCustomerUploadedImages();
        } catch (Exception $e) {
            Mage::helper('gomage_designer/ajax')->sendError($e->getMessage());
            return;
        }

        $this->loadLayout();
        $block = $this->getLayout()->getBlock('uploadedImages');
        Mage::helper('gomage_designer/ajax')->sendSuccess(array(
            'content' => $block->toHtml()
        ));
    }

    public function saveDesignAction()
    {
        $request = $this->getRequest();
        $isAjax = $request->isAjax();
        if (!$isAjax) {
            $this->norouteAction();
            return;
        }

        try {
            $design = $this->_saveDesign();			
			//$this->gravita_custom_mobile_images($design);

		$connection = Mage::helper('function/more')->getDbReadObject();

		$connection_write = Mage::helper('function/more')->getDbWriteObject();

		
		$gravitaCustomSessionId =Mage::getSingleton('core/session')->getGravitaCustomSessionId();
		
		$connection_write->query('update gomage_productdesigner_design_image set gravita_custom_session_id="'.$gravitaCustomSessionId.'" where design_id = "'.$design->getId().'"'); 
		 
		$connection_write->query('update gomage_productdesigner_design set gravita_custom_session_id="'.$gravitaCustomSessionId.'" where design_id = "'.$design->getId().'"');


			
            Mage::helper('gomage_designer/ajax')->sendSuccess(array('design_id' => $design->getId()));
        } catch (Exception $e) {
            Mage::helper('gomage_designer/ajax')->sendError($e->getMessage());
        }
    }

function gravita_custom_mobile_images($design)
{

		$connection = Mage::helper('function/more')->getDbReadObject();

		$connection_write = Mage::helper('function/more')->getDbWriteObject();


		$sql        = "Select * from gomage_productdesigner_design_image where design_id = '".$design->getId()."'";
		$rows       = $connection->fetchAll($sql);	

		$_product = Mage::getModel('catalog/product')->load($rows[0]['product_id']);
		
		$gravitaCustomSessionId =Mage::getSingleton('core/session')->getGravitaCustomSessionId();
		
		$connection_write->query('update gomage_productdesigner_design_image set gravita_custom_session_id="'.$gravitaCustomSessionId.'" where design_id = "'.$design->getId().'"'); 
		 
		$connection_write->query('update gomage_productdesigner_design set gravita_custom_session_id="'.$gravitaCustomSessionId.'" where design_id = "'.$design->getId().'"'); 
		 
		for($ii=0;$ii<count($rows);$ii++){	
			  $exclude_front_source_size =  getimagesize(Mage::getBaseDir().'/media/gomage/productdesigner/designs/catalog/product/'.basename($rows[$ii]['image']));
		}
/****************************************************************************************************************/

 
 /******************Code for fetching blank image for masking*********************/  
 $categoryIds = $_product->getCategoryIds();




 $graCatLevel = 0; 
 $graCatIndex = 0;
 foreach($categoryIds as $categoryIdsIndex => $categoryIdsVal)
  {
	$_category2 = Mage::getModel('catalog/category')->load($categoryIdsVal);
	$categoryLevel = $_category2->getLevel();
	if($graCatLevel < $categoryLevel){
	 $graCatLevel = $categoryLevel;
	 $graCatIndex = $categoryIdsIndex;
	} 
  }
 
		 
	$_category = Mage::getModel('catalog/category')->load(Mage::app()->getRequest()->getParam('url_para_cat_id')); 
	
	
  $path_to_parent_categories = explode('/',$_category->getPath());	
	 
	
	
	  //$graBlankImageFolderKey = $_category->getUrlKey(); 
	  
	  
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
		
		if((in_array(34,$ids) || in_array(75,$ids) || in_array(3,$ids) || in_array(66,$ids)) && $id_cat_path_counter == 4 /**For handling condition for mobile case and mobile skin**/)
		  continue;
		
		$idcat_obj = Mage::getModel('catalog/category')->load($idcat);		 
		$id_cat_path .= $sep.$idcat_obj->getUrlKey();
		$sep = '/';
	 }		
	

	if(in_array(3,$ids) || in_array(66,$ids || in_array(91,$ids) || in_array(4,$ids)) /**For handling condition for mobile case**/){
	
	  $folderURL = Mage::helper('function')->graGetFolderUrl($id_cat_path);		 
	  $folderPath = Mage::helper('function')->graGetFolderPath($id_cat_path);
	
	  if(!is_file($folderPath . DS . 'resized1000' . DS . 'mask.png'))
 	   $blank_backImagePath = Mage::helper('function')->resizeImg('mask.png', $exclude_front_source_size[0], $exclude_front_source_size[1],$folderURL,$folderPath,'resized1000');	   
	  else 	
	   $blank_backImagePath = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL).'media/blank_images/'.$id_cat_path.'/resized1000/mask.png';
	   
	 }
	else
	 { 
	    $folderURL = Mage::helper('function')->graGetFolderUrl($id_cat_path);		 
	    $folderPath = Mage::helper('function')->graGetFolderPath($id_cat_path);
	    
	  if(!is_file($folderPath . DS . 'resized1000' . DS . 'mask-front.png'))
	   $blank_frontImagePath = Mage::helper('function')->resizeImg('mask-front.png', $exclude_front_source_size[0], $exclude_front_source_size[1],$folderURL,$folderPath,'resized1000');	   
	  else	  
	   $blank_frontImagePath = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL).'media/blank_images/'.$id_cat_path.'/resized1000/mask-front.png';	
	   
	  if(!is_file($folderPath . DS . 'resized1000' . DS . 'mask-back.png')) 
 	   $blank_backImagePath = Mage::helper('function')->resizeImg('mask-back.png', $exclude_front_source_size[0], $exclude_front_source_size[1],$folderURL,$folderPath,'resized1000');	   
	  else 
	   $blank_backImagePath = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL).'media/blank_images/'.$id_cat_path.'/resized1000/mask-back.png'; 
	   
	 }

 /********************************************************************************/

//$array_ids = //your array of ids

for($ii=0;$ii<count($rows);$ii++){

$images =  $_product->getMediaGalleryImages();
 
 
 
 
 $Label = '';
 
    foreach($images as $_image2){
	
	        if($rows[$ii]['image_id'] == $_image2->getId())
	         $Label = $_image2->getLabel();
			 
			  if(in_array(3,$ids) || in_array(66,$ids) /*this condition for case product*/){
				
			   //This is exceptional case for handling wrong mobile case image label,				
			   $Label = 'Back';
			  } 
	 
			if(trim(strtolower($Label))=='front'){
			    //$exclude_front = Mage::helper('catalog/image')->init($_product, 'image', $_image2->getFile())->resize(1000,1000);
			  
			      $exclude_front =  $blank_frontImagePath;
			   
			}
			else if(trim(strtolower($Label))=='back' || in_array(3,$ids) || in_array(66,$ids) /*this condition for case product*/){
			   // $exclude_front = Mage::helper('catalog/image')->init($_product, 'image', $_image2->getFile())->resize(1000,1000);
			   
			      $exclude_front =  $blank_backImagePath;
			   
			}
	}
	
	
	
	
	
	
	  $exclude_front_source =  Mage::getBaseDir().'/media/gomage/productdesigner/designs/catalog/product/'.basename($rows[$ii]['image']);
		


		
		    /*$mask1 = imagecreatefrompng($exclude_front);
			$this->imagealphamask($exclude_front_source, $mask1);		
			imagepng($exclude_front_source, Mage::getBaseDir().'/media/gomage/productdesigner/designs/catalog/product'.$rows[0]['image']); */	
			
			
 $image = imagecreatefromjpeg($exclude_front_source);
 

 /*if($_POST['gra_custom_color'])
  {
    $gra_custom_color = explode(',',$_POST['gra_custom_color']);
  
		$im = imagecreatetruecolor($exclude_front_source_size[0], $exclude_front_source_size[1]);
		$white = imagecolorallocate($im, $gra_custom_color[0], $gra_custom_color[1], $gra_custom_color[2]);

		// Draw a white rectangle
		imagefilledrectangle($im, 0, 0, $exclude_front_source_size[0], $exclude_front_source_size[1], $white);

		// Save the image
		 
		 $frame = imagepng($im,Mage::getBaseDir().'/media/gomage/productdesigner/designs/catalog/product/temp.png');  
		 
		 
		 $frame = imagecreatefrompng(Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL).'media/gomage/productdesigner/designs/catalog/product/temp.png');
  }
 else */
 $frame = imagecreatefrompng($exclude_front);

 imagecopyresampled($image, $frame, 0, 0, 0, 0, $exclude_front_source_size[0], $exclude_front_source_size[1], $exclude_front_source_size[0], $exclude_front_source_size[1]);


		$white = imagecolorallocate($image, 255, 255, 255);

imagecolortransparent($image, $white);
 # Save the image to a file
 //imagepng($image, '/path/to/save/image.png');

 # Output straight to the browser.
 //header( "Content-type: image/png");
 
 $pathinfo = pathinfo($rows[$ii]['image']);
 $saveIMageNmae = $pathinfo['filename'].'_product.jpg';
 imagejpeg($image,Mage::getBaseDir().'/media/gomage/productdesigner/designs/catalog/product/'.$saveIMageNmae);	

 
 	//$connection_write->query('update gomage_productdesigner_design_image set image="\\\\'.$saveIMageNmae.'",skin_image = "\\'.$rows[$ii]['image'].'" where id = "'.$rows[$ii]['id'].'"');
 	$connection_write->query('update gomage_productdesigner_design_image set image="'.$saveIMageNmae.'",skin_image = "\\'.$rows[$ii]['image'].'" where id = "'.$rows[$ii]['id'].'"');
    
}


}	
function imagealphamask( &$picture, $mask ) {
	
	$xSize = '';$ySize = '';$newPicture = '';$tempPic = '';
	$xSize = imagesx( $picture );
	$ySize = imagesy( $picture );
	$newPicture = imagecreatetruecolor( $xSize, $ySize );
	imagesavealpha( $newPicture, true );
	imagefill( $newPicture, 0, 0, imagecolorallocatealpha( $newPicture, 0, 0, 0, 127 ) );
	if( $xSize != imagesx( $mask ) || $ySize != imagesy( $mask ) ) {
		$tempPic = imagecreatetruecolor( $xSize, $ySize );
		imagecopyresampled( $tempPic, $mask, 0, 0, 0, 0, $xSize, $ySize, imagesx( $mask ), imagesy( $mask ) );
		imagedestroy( $mask );
		$mask = $tempPic;
	}
	for( $x = 0; $x < $xSize; $x++ ) 	{
	  for ($y = 0; $y < $ySize; $y++) {
		  $alpha = imagecolorsforindex($mask, imagecolorat($mask, $x, $y));
		  $alpha = 127 - floor($alpha['red'] / 2);
		  if (127 == $alpha) {
			continue;
		  }
		  $color = imagecolorsforindex($picture, imagecolorat($picture, $x, $y));
		  imagesetpixel($newPicture, $x, $y, imagecolorallocatealpha(
		  $newPicture, $color['red'], $color['green'], $color['blue'], $alpha));
		}
	}
	imagedestroy( $picture );
	$picture = $newPicture;
}	
	
    protected function _getDesignPriceHtml()
    {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('gomage_designer_design_price');
        $layout->generateXml();
        $layout->generateBlocks();
        return $layout->getOutput();
    }

    protected function _getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }

    protected function getSessionId()
    {
        return $this->_getCustomerSession()->getEncryptedSessionId();
    }

    protected function prepareFilesArray($files)
    {
        $filesArray = array();
        foreach ($files as $key => $values) {

            foreach ($values as $valueKey => $value) {
                $filesArray[$valueKey][$key] = $value;
            }
        }
        return $filesArray;
    }

    protected function _getTitle()
    {
        $title = Mage::getStoreConfig('gomage_designer/general/page_title');
        return $title ?: Mage::getStoreConfig('design/head/default_title');
    }

}
