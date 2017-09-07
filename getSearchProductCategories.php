<?php
# Uncomment the next three lines if you’re calling this outside of Magento
require_once 'app/Mage.php';
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$refererUrl = Mage::app()->getRequest()->getServer('HTTP_REFERER');


if(!strpos($refererUrl,$_SERVER['HTTP_HOST']))
exit;
# If you’re calling this from inside a phtml file then you can use something like this to get the category ID
#$category_id = $this->getCurrentCategory()->getId();
# or use the following line:

		$write = Mage::helper('function/more')->getDbWriteObject();	
		$read = Mage::helper('function/more')->getDbReadObject();

$search_rew_params = $_POST['search_rew_params'];
$search_rew_params = json_decode($search_rew_params,true);

$cat_arr = $search_rew_params['categories_inc'];
$urlWithoutParameters = substr($_SERVER['HTTP_REFERER'],0,strrpos($text,'?'));

$search_current_cat = $_POST["search_current_cat"];
$catId = $_POST['catId'];


						$children = Mage::getModel('catalog/category')->getCategories($catId);
						
                        
						
						$childrenCategoryArray = array();
							foreach ($children as $category) {
									$childrenCategoryArray[] = $category->getId();
							}


//////////////////////////////Code For Matching Product Title//////////////////////////////

					function drawSearchAccordion($parentCategoryNameValArr){
					global $search_rew_params;
					global $search_current_cat;
					global $urlWithoutParameters;
					global $cat_arr;
					$request = Mage::app()->getRequest();	
						
                    echo '<ul>';
                    foreach ($parentCategoryNameValArr as $parentCategoryNameValArrVal) 
                    { 
					$temp_cat_search_arr = $cat_arr;
					
					$search_rew_params['cat'] = $parentCategoryNameValArrVal;
					
					$search_rew_params['categories'] = array($parentCategoryNameValArrVal);
					
					/*$temp_cat_search_arr[] = $parentCategoryNameValArrVal;
					
					if (($key = array_search($search_current_cat, $temp_cat_search_arr)) !== false) {
							unset($temp_cat_search_arr[$key]);
					}*/
					
					//echo $search_rew_params['categories_inc'];exit;
					
					//$search_rew_params['categories_inc'] = implode('_',$search_rew_params['categories_inc']);
					
					 
$urlParamsSearch = array();
foreach ($search_rew_params as $search_name=>$search_value){
	
	if(is_array($search_value)){
	foreach($search_value as $search_value_val)	
	$urlParamsSearch[] = $search_name.'[]='.urlencode($search_value_val);
	}
	else
    $urlParamsSearch[] = $search_name.'='.urlencode($search_value);
}
$urlParamsSearch = implode('&', $urlParamsSearch);
					
						$sub_cats = Mage::getModel('catalog/category')->load($parentCategoryNameValArrVal);
						//print_r($sub_cats);
						if(isset($params['texturedPrdStr']) && $params['texturedPrdStr'] != '')
						{
							$str = '?texturedPrdStr='.$params['texturedPrdStr'];
						}
						
						$children = Mage::getModel('catalog/category')->getCategories($parentCategoryNameValArrVal);
						
                        
						
						$childrenCategoryArray = array();
							foreach ($children as $category) {
									$childrenCategoryArray[] = $category->getId();
							}
						
						if(count($childrenCategoryArray) > 0){	
						echo '<li><a href="'.$urlWithoutParameters.'?'.$urlParamsSearch.'"><strong>'.$sub_cats->getName().'</strong></a>';
							//print_r($childrenCategoryArray);
						 drawSearchAccordion($childrenCategoryArray);
						echo '</li>';
						}
						else{
							
							 echo '<li><a href="'.$urlWithoutParameters.'?'.$urlParamsSearch.'">'.$sub_cats->getName().'</a></li>';
						}
                    }				
                    echo '</ul>';
					}
					
					
drawSearchAccordion($childrenCategoryArray);
?>