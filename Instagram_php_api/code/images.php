<?php session_start();
 include_once('../../app/Mage.php');
Mage::app();
 	 
	
 
$img_arr = $_SESSION['bshm_inst_usr_imgs'];
 
?>
<link rel="stylesheet" type="text/css" href="<?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN)?>frontend/rwd/default/css/jquery.fancybox.css" />

<link rel="stylesheet" type="text/css" href="<?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN)?>frontend/rwd/default/css/styles.css" />


  <div id="show_div_fb" class="choose-stroke" style="width:700px;height:500px;overflow:auto;">
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
	 <div class="image-ppc"><img class="clipart-image" src="<?php echo $img_arr[$i]?>" data-origin-url="<?php echo $img_arr[$i]?>" onclick="onClickUserInstImages(this.src)"></div>
	 
	<?php	
						 
			}
$_SESSION['bshm_inst_usr_imgs']='';			
	?>
			  </div>
			</div>
                  
				  </div>  
				  
<script type="text/javascript">
function onClickUserInstImages(imgSrc)
{
parent.jQuery('#uploadedImages_fb_img').attr('src',imgSrc);
parent.jQuery('#uploadedImages_fb_img').attr('data-origin-url',imgSrc);
parent.jQuery('#uploadedImages_fb_img').click();
parent.jQuery.fancybox.close();
parent.jQuery('#social_media_img_container').append('<li><img class="clipart-image" src="'+imgSrc+'" data-origin-url="'+imgSrc+'"><span onclick="jQuery(this).parent().remove();">X</span></li>')
}

</script>				  