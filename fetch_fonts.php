<?php
include_once('app/Mage.php');
Mage::app();
$refererUrl = Mage::app()->getRequest()->getServer('HTTP_REFERER');
if(!strpos($refererUrl,$_SERVER['HTTP_HOST']))
exit;


		$write = Mage::helper('function/more')->getDbWriteObject();	
		$read = Mage::helper('function/more')->getDbReadObject();

		$collection = Mage::getModel('gomage_designer/fontswithcat_category')->getCollection();

$collection->getSelect()->order('name','ASC');


$params = Mage::app()->getFrontController()->getRequest()->getParams();
$id = $params["id"]; 
$get_cat_id = $params["cat_id"];
if($get_cat_id == 0 && $get_cat_id!='')
$firstCategory = 0;
else if(!$get_cat_id){
$firstCategory = $collection->getFirstItem();
$firstCategory = $firstCategory['category_id'];
}
else
$firstCategory = $get_cat_id;


if($firstCategory == 65)
			$defaultFonts = Mage::getModel('gomage_designer/config_source_font')->toOptionArray();

            $fonts = Mage::getResourceModel('gomage_designer/font_collection');
            $fonts->addFieldToFilter('disabled', '0')->addFieldToFilter('category_id', $firstCategory)->setOrder('position', 'ASC');
			
			
            

?>
<html>
<head>
<style type="text/css">
.demoTest{
    left: 0px !important;
    
    margin: 0px !important;
   
    top: 0px !important;
}
</style>
<?php /*?>	
        <select class="font-picker" id="font-selector">
        <?php foreach ($defaultFonts as $font):?>
            <option value="<?php echo $font['value']; ?>" <?php echo Mage::getStoreConfig('gomage_designer/text/font') == $font['value'] ? 'selected="selected"' : ''?>><?php echo $font['label']; ?></option>
        <?php endforeach;?>
        <?php 
		
		foreach($fonts as $font): 
        
		
        if ($font->getLabel()) {
            $getFontName = $font->getLabel();
        }else{
        $fontName = $font->getFont();
        $fontName = substr(strrchr($fontName, '/'), 1);
        $getFontName = pathinfo($fontName, PATHINFO_FILENAME);		
		
		
                $fontNameToDisplay = str_replace('_',' ',$getFontName);
                $fontNameToDisplay = str_replace('-',' ',$fontNameToDisplay);
                $fontNameToDisplay = ucwords(strtolower($fontNameToDisplay));
        }
        ?>
            <option value="<?php echo $getFontName; ?>"><?php echo $fontNameToDisplay; ?></option>
        <?php endforeach; ?>
        </select> 
<?php */?>

<?php 
if($firstCategory > 0){
foreach($fonts as $font):
				 
				if ($font->getLabel()) {
					$fontName = $font->getLabel();
				}
				else{
				$fontName = $font->getFont();
				$fontName = substr(strrchr($fontName, '/'), 1);
				$fontName = pathinfo($fontName, PATHINFO_FILENAME);				
				}
				
                $fontNameIdToDisplay = str_replace('_','',$fontName);
                $fontNameIdToDisplay = str_replace('-','',$fontNameIdToDisplay);
                $fontNameIdToDisplay = str_replace(' ','',$fontNameIdToDisplay);
                $fontNameIdToDisplay = ucwords(strtolower($fontNameIdToDisplay));
        $fontPath = str_replace(Mage::getSingleton('gomage_designer/font_gallery_config')->getBaseTmpMediaUrl(), '', $fontName);
        $fontPath = str_replace(Mage::getSingleton('gomage_designer/font_gallery_config')->getBaseMediaUrl(), '', $fontName);
?>
<style type="text/css">
    @font-face {
        font-family: <?php echo $fontName;?>;
        src: url("<?php echo Mage::getSingleton('gomage_designer/font_gallery_config')->getBaseMediaUrl() . $font->getFont();?>");
    }
	#<?php echo $fontNameIdToDisplay?>{
		font-family: <?php echo $fontName;?>;
	}	
</style>	
<script type="text/javascript">	
		parent.jQuery("head").prepend("<style type=\"text/css\">" + 
                                "@font-face {\n" +
                                    "\tfont-family: \" <?php echo $fontName;?>\";\n" + 
                                    "\tsrc: url('<?php echo Mage::getSingleton('gomage_designer/font_gallery_config')->getBaseMediaUrl() . $font->getFont();?>') ;\n" + 
                                "}\n" + "</style>");
</script>	
<?php endforeach;
}
?>
		
</head>
<body>
<div id="srightHTML">
<div class="sleft" style="float:left;width:150px;">

<ul>

<?php /*?><li><a href="javascript:void(0)" onclick="jQuery('#filter_loading_image').show();load_fonts_by_cat('0')" <?php if($firstCategory == 0) echo 'class="active"'?>>Default Fonts</a></li><?php */?>

<?php

foreach($collection as $collItem)
 {
 
 if($collItem['category_id'] == 1)
  continue;
 

?>
<li><a href="javascript:void(0)" onclick="jQuery('#filter_loading_image').show();load_fonts_by_cat('<?php echo $collItem['category_id']?>')" <?php if($firstCategory == $collItem['category_id']) echo 'class="active"'?>> <?php echo $collItem['name']?> </a></li>
<?php  
   
 }
?>

                                              </ul>

</div>
<div class="sright" style="float:left;margin-left:20px;width:440px;">        
        <?php 
		if($firstCategory == 65){
		foreach ($defaultFonts as $font):?>
           <?php /*?> <option value="<?php echo $font['value']; ?>" <?php echo Mage::getStoreConfig('gomage_designer/text/font') == $font['value'] ? 'selected="selected"' : ''?>><?php echo $font['label']; ?></option><?php */?>
			
			<div class="image-ppc-font bhishoom_font_selector" onclick="parent.jQuery('#font-selector').val('<?php echo $font['value']; ?>').trigger('change'); parent.objCallDirect.customBhishoom_observeFontChange();parent.jQuery.fancybox.close();" style="font-size:24px; font-family:<?php echo $font['label']?>;cursor:pointer;padding: 0px !important;"><?php echo $font['label']; ?></div>
			
        <?php endforeach;
		 }
		
		//else
		//{
		foreach($fonts as $font): 
        
		$fontName = $font->getFont();
        if ($font->getLabel()) {
            $getFontName = $font->getLabel();
        }else{
        //$fontName = $font->getFont();
        $fontName = substr(strrchr($fontName, '/'), 1);
        $getFontName = pathinfo($fontName, PATHINFO_FILENAME);		
		}
		
		$fontValue = $getFontName;
		
                $fontNameToDisplay = str_replace('_',' ',$getFontName);
                $fontNameToDisplay = str_replace('-',' ',$fontNameToDisplay);
                $fontNameToDisplay = ucwords(strtolower($fontNameToDisplay));
 
                $fontNameIdToDisplay = str_replace('_','',$getFontName);
                $fontNameIdToDisplay = str_replace('-','',$fontNameIdToDisplay);
                $fontNameIdToDisplay = str_replace(' ','',$fontNameIdToDisplay);
                $fontNameIdToDisplay = ucwords(strtolower($fontNameIdToDisplay));
        
        ?>
         <?php /*?>   <option value="<?php echo $getFontName; ?>"><?php echo $fontNameToDisplay; ?></option><?php */?>
			
			
<div class="image-ppc-font bhishoom_font_selector" onclick="parent.jQuery('#font-selector').val('<?php echo $fontValue; ?>'); parent.objCallDirect.customBhishoom_observeFontChange();parent.jQuery.fancybox.close();" id="<?php echo $fontNameIdToDisplay?>" style="font-size:24px;cursor:pointer;padding: 5px !important;display:block !important;width:200px; height:100px;"><span class="demoTest"><?php echo $fontNameToDisplay; ?></span></div>				
			
			
        <?php endforeach; 
		//}
		?>
</div>
</div>

</body>
</html>		
	