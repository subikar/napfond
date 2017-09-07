<?php session_start();
 include_once('../../app/Mage.php');
Mage::app();
 	 
	
 
$img_arr = $_SESSION['bshm_inst_usr_imgs'];
/* 
?>
<link rel="stylesheet" type="text/css" href="<?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN)?>frontend/rwd/default/css/jquery.fancybox.css" />

<link rel="stylesheet" type="text/css" href="<?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN)?>frontend/rwd/default/css/styles.css" />
<?php */?>

	<h2>Gallery</h2> 
	<div class="sright" style="width:100%">
	    <div id="filter_loading_image" style="background-color: #ffffff;height: 80%;left: 0;opacity: 0.5;position: absolute;width: 100%;z-index: 555;padding-top: 1%; overflow:hidden;display:none;">
		   <img src="<?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN)?>frontend/rwd/default/images/opc-ajax-loader.gif" style="margin:0 auto"/>
		 </div>	
	<div>
	<?php
	        $show = '';
			for($i=0;$i<count($img_arr);$i++)
			{ 
	?>
	 <div class="image-ppc"><img class="clipart-image" src="<?php echo $img_arr[$i]?>" alt="<?php echo Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL);?>Instagram_php_api/read_image.php?bhishoom_img_url=<?php echo $img_arr[$i];?>" data-origin-url="<?php echo Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL);?>Instagram_php_api/read_image.php?bhishoom_img_url=<?php echo $img_arr[$i];?>" onclick="onClickUserInstImages22(this.alt)"></div>
	 
	<?php	
						 
			}
$_SESSION['bshm_inst_usr_imgs']='';			
	?>
  </div>
</div>