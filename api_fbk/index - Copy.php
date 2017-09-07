<?php session_start();
 include_once('../app/Mage.php');
Mage::app();
require 'src/facebook.php';  // Include facebook SDK file
//require 'functions.php';  // Include functions
$facebook = new Facebook(array(
  'appId'  => '753880228035779',   // Facebook App ID 
  'secret' => '082370d776e5bd823c4d3cbf4bbe7e57',  // Facebook App Secret
  'cookie' => true 
));

    $user = $facebook->getUser(); 
	
	 
	
 if($user) {
   $access_token = $facebook->getAccessToken();
 
  //$albums = $facebook->api('/1376631492640455/photos');
  $albums2 = $facebook->api('/me/albums');

  $albums2 = $albums2['data'];
  
  //echo '<pre>';
  //print_r($albums); 
  
  //echo '<pre>';
  //print_r($albums2);exit; 
}
 
?>

<script type="text/javascript">
 var mytestVar = [<?php $comma = '';for($i=0;$i<count($albums2);$i++){ echo $comma.$albums2[$i]['id']; $comma=', ';}?>];
</script>


<link rel="stylesheet" type="text/css" href="<?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN)?>frontend/rwd/default/css/jquery.fancybox.css" />

<link rel="stylesheet" type="text/css" href="<?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN)?>frontend/rwd/default/css/styles.css" />


  <div id="show_div_fb" class="choose-stroke" style="width:700px;height:500px;overflow:auto;">
	<h2>Gallery</h2>
	<div class="sleft">
	  <ul>
	<?php
					
						
				for($i=0;$i<count($albums2);$i++)
				{ 
				?>
					<li>
					  <div onclick="showHideDiv('<?php echo $albums2[$i]['id']?>')"> <?php echo ($albums2[$i]['name']);?> </div>
					</li>
				<?php	
				}
	?>
                      </ul>
                    </div>
					
					
<script type="text/javascript">

	function showHideDiv(divid)
     {  
	   for(ii=0;ii<mytestVar.length;ii++)
	   {
	      if(mytestVar[ii]==divid)
		   document.getElementById(mytestVar[ii]).style.display = '';
		  else  
		   document.getElementById(mytestVar[ii]).style.display = 'none'; 
	   }
	 
	 }
</script>			   
				
					
					
			<div class="sright">
					 <div id="filter_loading_image" style="background-color: #ffffff;height: 80%;left: 0;opacity: 0.5;position: absolute;width: 100%;z-index: 555;padding-top: 1%; overflow:hidden;display:none;">
						<img src="<?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN)?>frontend/rwd/default/images/opc-ajax-loader.gif" style="margin:0 auto"/>
					 </div>	
			  <div>
	<?php
	        $show = '';
			for($i=0;$i<count($albums2);$i++)
			{ 
			
			    if($show == 'yes')
			     $style='display:none';
				else 
				 $style='';
				 
				 $show = 'yes';
				 
				echo '<div id="'.$albums2[$i]['id'].'" style="'.$style.'">';	
 
				$albumsPhotos = $facebook->api('/'.$albums2[$i]['id'].'/photos');
				
				$albumsPhotos = $albumsPhotos['data'];
				
				
				//display image
					for($ii=0;$ii<count($albumsPhotos);$ii++)
					{
					
						$albumsPhotosImages = $albumsPhotos[$ii]["images"];
					
						/*for($jj=0;$jj<count($albumsPhotosImages);$jj++)
						{*/
							//echo '<img src="'.$img_folder.$image.'">';
							?><div class="image-ppc"><img class="clipart-image" src="<?php echo $albumsPhotosImages[4]['source']?>" data-origin-url="<?php echo $albumsPhotosImages[4]['source']?>" onclick="onClickUserInstImages(this.src)"></div><?php	
						//}
					}			

				 echo '</div>';	
			}		
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