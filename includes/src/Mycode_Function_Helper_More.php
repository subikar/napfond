<?php
class Mycode_Function_Helper_More extends Mage_Core_Helper_Abstract
{

	private static $matchCatIdsSearch;
	private $dbRead;
	private $dbWrite;
	private $baseUrl;
	private $checkMobile;

	public function __construct() { 
		self::$matchCatIdsSearch = array();
		$this->dbRead = Mage::getSingleton('core/resource')->getConnection('core_read');
		$this->dbWrite = Mage::getSingleton('core/resource')->getConnection('core_write');
		$this->baseUrl = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL);
		$this->checkMobile = $this->is_mobile_bhishoom();
	}	
	
	public function removeManaFilter($OptionValId,$currentUrl)
	{
	  
			 if(strpos($currentUrl,$OptionValId.'_') > 0)
				$redirectedUrl = str_replace($OptionValId.'_','',$currentUrl);
			 elseif(strpos($currentUrl,'_'.$OptionValId) > 0)
				$redirectedUrl = str_replace('_'.$OptionValId,'',$currentUrl);
			 elseif(strpos($currentUrl,$OptionValId) > 0)
				$redirectedUrl = str_replace($OptionValId,'',$currentUrl);
			 else
				$redirectedUrl = $currentUrl;
				
			 return $redirectedUrl;	
	}
 
	
	public function getDbReadObject(){		
		return $this->dbRead;
	}
	
	public function getDbWriteObject(){		
		return $this->dbWrite;
	}
	
	public function getBhishoomSiteBaseUrl(){		
		return $this->baseUrl;
	}
	
	public function chkIfmobileBhishomm(){		
		return $this->checkMobile;
	}
	
	public function fixQueryStringUrl($url){
		$cond = true;
		$ii=0;
		while($cond)
		{
			if(strpos($url,'['.$ii.']') > 0){				
			$url = str_replace('['.$ii.']','[]',$url);
			$ii++;
			}
			else
				$cond = false;
		}
		
		return $url;
	}
 
	public function updateProductsNameInCart()
    {	
		$quote=Mage::getSingleton('checkout/session')->getQuote();
		$quoteid=$quote->getId();	

			
		$connection = $this->dbRead;
		$write = $this->dbWrite;	
		$read = $this->dbRead;
	 
		$query = 'SELECT * FROM sales_flat_quote_item where quote_id="'.$quoteid.'"';
		
		
		$itemDetailsAll = $read->fetchAll($query);
		
		foreach($itemDetailsAll as $itemDetails)
		{
			$nameSuffix = $itemDetails['nameSuffix'];
			$name = $itemDetails['name'];
			$newName = $name.' '.$nameSuffix ;		
			$item_id = $itemDetails['item_id'];
			$parent_item_id = $itemDetails['parent_item_id'];
			$prdURL = $itemDetails['prdURL'];
			
			
			
			$query = "update sales_flat_quote_item set name = '".addslashes($newName)."' where item_id = '".$item_id."'";
			$write->query($query);	
		   if($parent_item_id > 0){	
			$query = "update sales_flat_quote_item set name = '".addslashes($newName)."',nameSuffix='".$nameSuffix."',prdURL='".$prdURL ."' where item_id = '".$parent_item_id."'";
			$write->query($query);	
			}	
		}
	
		
	}
 
	public function getShippingPrice(){
		$totalItemInCart = Mage::helper('checkout/cart')->getSummaryCount();
		$mainItem = 1;
		$thresHoldItem = $totalItemInCart - 1;
		
		$mainShippinPrice = Mage::getStoreConfig('mycustom_section/mycustom_group/mycustom_field');
		$thresHoldShippinPrice = Mage::getStoreConfig('mycustom_section/mycustom_group/mycustom_field2');
		$freeShippingCharge = Mage::getStoreConfig('mycustom_section/mycustom_group/mycustom_field3');


	//$resource = Mage::getSingleton('core/resource');
	$readConnection = $this->dbWrite;
	$writeConnection = $this->dbWrite;

	$quoteId = Mage::helper('checkout/cart')->getQuote()->getId();
	
	$subTotal = $readConnection->fetchOne("SELECT subtotal FROM sales_flat_quote where entity_id='".$quoteId."'");
	
	$subTotalWithDiscount = $readConnection->fetchOne("SELECT subtotal_with_discount FROM sales_flat_quote where entity_id='".$quoteId."'");
			
			
	$totalGiftCertificatItems = $readConnection->fetchOne('SELECT count(*) as totalGiftCertificatItems FROM sales_flat_quote_item WHERE product_id = "113906" and quote_id="'.$quoteId.'"');		
			
	$thresHoldItem  = $thresHoldItem - $totalGiftCertificatItems;	
			
		if($subTotalWithDiscount > 0)
		$subTotal = $subTotalWithDiscount;

		if($subTotal > $freeShippingCharge)
		$price = 0;	
		else
		$price = $mainShippinPrice + ($thresHoldShippinPrice * $thresHoldItem);

		return $price;
	}
 


 public function getMobileMenuBhishoom(){
$menuStr = '';

$read = $this->dbRead; // To read from the database
$write = $this->dbWrite; // To write to the database
/*$categoryId = 3;    
$products = Mage::getSingleton('catalog/category')->load($categoryId)->getProductCollection();
 foreach($products as $product){
	$productid =  $product->getId();
	 
	 $productt = Mage::getModel('catalog/product')->load($productid);
	 
	 echo $productt->getUrlPath()."<br>";
	 
}*/


$menuStr = $menuStr.'<ul class="mainNav navGroup1">
<li><a href="#"><i class="mi mi-mobileCase"></i>Mobile Skin<span><i class="fa fa-plus"></i></span></a>
<ul class="subNav level1">';

$cat = Mage::getModel('catalog/category')->load(34);
$subcats = $cat->getChildren();

foreach(explode(',',$subcats) as $subCatid)
{
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
	  
     $menuStr = $menuStr.'<li><a href="#">'.$name.'<span><i class="fa fa-plus"></i></a></span>
	 <ul class="subNav level2">';
	
    $parent = Mage::getModel('catalog/category')->load($_category->getId())->getChildren();
	$sub_subcats = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->setStoreId(Mage::app()->getStore()->getId())->addAttributeToSort('position', 'asc')->addIdFilter($parent)->load();  	
	// $sub_cat = Mage::getModel('catalog/category')->load($_category->getId());
    // $sub_subcats = $sub_cat->getChildren();
    foreach($sub_subcats as $sub_subCatids)
    {
         $sub_subCatid = $sub_subCatids->getId();
		  
		  $_sub_category = Mage::getModel('catalog/category')->load($sub_subCatid);
          if($_sub_category->getIsActive()) {
			  $suburl = str_replace('index.php/','',$_sub_category->getURL());
			  $subname = $_sub_category->getName();
			  $menuStr = $menuStr.'<li><a href="'.$suburl.'">'.$subname.'</a></li>';             
           }
     }
  $menuStr = $menuStr.'</li></ul>';
  }
}



$menuStr = $menuStr.'

</ul>
</li>

<li><a href="#"><i class="mi mi-mobileSkin"></i>Mobile Case<span><i class="fa fa-plus"></i></span></a>
<ul class="subNav level1">';


$cat = Mage::getModel('catalog/category')->load(3);
$subcats = $cat->getChildren();

foreach(explode(',',$subcats) as $subCatid)
{
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
 
   $menuStr = $menuStr.'<li><a href="#">'.$name.'<span><i class="fa fa-plus"></i></span></a>
     <ul class="subNav level2">';
 	
	 $parent = Mage::getModel('catalog/category')->load($_category->getId())->getChildren();
	$sub_subcats = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->setStoreId(Mage::app()->getStore()->getId())->addAttributeToSort('position', 'asc')->addIdFilter($parent)->load();   
	// $sub_cat = Mage::getModel('catalog/category')->load($_category->getId());
    // $sub_subcats = $sub_cat->getChildren();
	
    foreach($sub_subcats as $sub_subCatids)
    {
		$sub_subCatid = $sub_subCatids->getId();
          $_sub_category = Mage::getModel('catalog/category')->load($sub_subCatid);
          if($_sub_category->getIsActive()) {
			  $suburl = str_replace('index.php/','',$_sub_category->getURL());
			  $subname = $_sub_category->getName();			  
			  $menuStr = $menuStr.'<li><a href="'.$suburl.'">'.$subname.'</a>
</li>';            
           }
     }
 $menuStr = $menuStr.'</li></ul>';
  }
}



$menuStr = $menuStr.'</ul>
</li>


<li><a href="#"><i class="mi mi-tshirt"></i>T-Shirts<span><i class="fa fa-plus"></i></span></a>
<ul class="subNav level1">';

$cat = Mage::getModel('catalog/category')->load(4);
$subcats = $cat->getChildren();

foreach(array_reverse(explode(',',$subcats)) as $subCatid)
{
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  str_replace('index.php/','',$_category->getURL());
	  $name = $_category->getName();
	
    $menuStr = $menuStr.'<li><a href="'.$url.'">'.$name.'</a>
</li>';
     
	
  }
}
$menuStr = $menuStr.'</li></ul>';

$menuStr = $menuStr.'

<li><a href="#"><i class="mi mi-laptop"></i>LapTop Skins<span><i class="fa fa-plus"></i></span></a>
<ul class="subNav level1">';

// $cat = Mage::getModel('catalog/category')->load(8);
// $subcats = $cat->getChildren();
$parent = Mage::getModel('catalog/category')->load(8)->getChildren();
$subcats = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->setStoreId(Mage::app()->getStore()->getId())->addAttributeToSort('position', 'asc')->addIdFilter($parent)->load(); 
foreach($subcats as $subCatids)
{
	$subCatid = $subCatids->getId();
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  str_replace('index.php/','',$_category->getURL());
	  $name = $_category->getName();
	 
    $menuStr = $menuStr.'<li><a href="'.$url.'">'.$name.'</a></li>';
  	
	  
	
    
  }
}
$menuStr = $menuStr.'</li></ul>';
$menuStr = $menuStr.'

<li><a href="#"><i class="mi mi-tablet"></i>Tablet Skins<span><i class="fa fa-plus"></i></span></a>
<ul class="subNav level1">';

// $cat = Mage::getModel('catalog/category')->load(478);
// $subcats = $cat->getChildren();
$parents = Mage::getModel('catalog/category')->load(478)->getChildren();
$subcats = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->setStoreId(Mage::app()->getStore()->getId())->addAttributeToSort('position', 'asc')->addIdFilter($parents)->load(); 



foreach($subcats as $subCatids)
{
	$subCatid = $subCatids->getId();
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
	$menuStr = $menuStr.'<li><a href="#">'.$name.'<span><i class="fa fa-plus"></i></span></a>';    
 
  $menuStr = $menuStr.' <ul class="subNav level2">';
 
  $parent = Mage::getModel('catalog/category')->load($_category->getId())->getChildren();
	
	
  $sub_subcats = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->setStoreId(Mage::app()->getStore()->getId())->addAttributeToSort('position', 'asc')->addIdFilter($parent)->load();  
 if(count($sub_subcats)>0){
 foreach($sub_subcats as $sub_subCatids)
    {
		$sub_subCatid =  $sub_subCatids->getId(); 
		
		
          $_sub_category = Mage::getModel('catalog/category')->load($sub_subCatid);
          if($_sub_category->getIsActive()) {
			  $suburl = str_replace('index.php/','',$_sub_category->getURL());
			  $subname = $_sub_category->getName();
			   
					$menuStr = $menuStr.'<li><a href="'.$suburl.'"> '.$subname.'</a></li>';			  
	  
             
           }
     }
 }
 else{
	   $catTemp = Mage::getModel('catalog/category')->load($subCatid);
	   $suburl = str_replace('index.php/','',$catTemp->getURL());
 	   $subname = $catTemp->getName();
	   $menuStr = $menuStr.'<li><a href="'.$suburl.'"> '.$subname.'</a></li>';	
	}
	 
 
  
 $menuStr = $menuStr.'</ul></li>';

  }
}


$menuStr = $menuStr.'</ul></li>';
$menuStr = $menuStr.'
<li><a href="#"><i class="mi mi-shopByInterest"></i>Shop by interest<span><i class="fa fa-plus"></i></span></a>
<ul class="subNav level1">
 <li> <a href="'.$this->baseURLData.'/create.html?case_style=189&amp;p=1&amp;ref=shopbyshop">3D</a> </li>
<li> <a href="'.$this->baseURLData.'/create.html?case_style=41&amp;p=1&amp;ref=shopbyshop">Animals</a> </li>
<li><a href="'.$this->baseURLData.'/create.html?case_style=331&amp;p=1&amp;ref=shopbyshop">Auto &amp; Transportation</a> </li>
<li><a href="'.$this->baseURLData.'/create.html?case_style=58&amp;p=1&amp;ref=shopbyshop">Comic &amp; Cartoon</a> </li>
<li><a href="'.$this->baseURLData.'/create.html?case_style=48&amp;p=1&amp;ref=shopbyshop">Cities &amp; Travel</a> </li>
<li><a href="'.$this->baseURLData.'/create.html?case_style=193&amp;p=1&amp;ref=shopbyshop">Desi</a> </li>
<li><a href="'.$this->baseURLData.'/create.html?case_style=109&amp;p=1&amp;ref=shopbyshop">Floral</a> </li>
<li><a href="'.$this->baseURLData.'/create.html?case_style=38&amp;p=1&amp;ref=shopbyshop">Famous</a> </li>
<li><a href="'.$this->baseURLData.'/create.html?case_style=67&amp;p=1&amp;ref=shopbyshop">Fashion</a> </li>
<li><a href="'.$this->baseURLData.'/create.html?case_style=333&amp;p=1&amp;ref=shopbyshop">Flags</a> </li>
<li><a href="'.$this->baseURLData.'/create.html?case_style=227&amp;p=1&amp;ref=shopbyshop">Graphic</a> </li>
<li><a href="'.$this->baseURLData.'/create.html?case_style=335&amp;p=1&amp;ref=shopbyshop">Gym &amp; Health</a> </li>
<li><a href="'.$this->baseURLData.'/create.html?case_style=332&amp;p=1&amp;ref=shopbyshop">Humour</a> </li>
<li><a href="'.$this->baseURLData.'/create.html?case_style=337&amp;p=1&amp;ref=shopbyshop">Hearts</a></li>
<li> <a href="'.$this->baseURLData.'/create.html?case_style=329&amp;p=1&amp;ref=shopbyshop">Illustration</a> </li>
<li> <a href="'.$this->baseURLData.'/create.html?case_style=113&amp;p=1&amp;ref=shopbyshop">Jerseys</a> </li>
<li> <a href="'.$this->baseURLData.'/create.html?case_style=200&amp;p=1&amp;ref=shopbyshop">Movies &amp; Television</a> </li>
<li> <a href="'.$this->baseURLData.'/create.html?case_style=225&amp;p=1&amp;ref=shopbyshop">Music</a> </li>
<li> <a href="'.$this->baseURLData.'/create.html?case_style=199&amp;p=1&amp;ref=shopbyshop">Nature &amp; Landscape</a> </li>
<li> <a href="'.$this->baseURLData.'/create.html?case_style=192&amp;p=1&amp;ref=shopbyshop">Party</a> </li>
<li> <a href="'.$this->baseURLData.'/create.html?case_style=195&amp;p=1&amp;ref=shopbyshop">Political</a> </li>
<li> <a href="'.$this->baseURLData.'/create.html?case_style=334&amp;p=1&amp;ref=shopbyshop">Profession &amp; Personality</a> </li>
<li> <a href="'.$this->baseURLData.'/create.html?case_style=224&amp;p=1&amp;ref=shopbyshop">Quirky</a> </li>
<li> <a href="'.$this->baseURLData.'/create.html?case_style=198&amp;p=1&amp;ref=shopbyshop">Religion &amp; Spirituality</a> </li>
<li> <a href="'.$this->baseURLData.'/create.html?case_style=196&amp;p=1&amp;ref=shopbyshop">Retro</a> </li>
<li> <a href="'.$this->baseURLData.'/create.html?case_style=56&amp;p=1&amp;ref=shopbyshop">Sports</a> </li>
<li> <a href="'.$this->baseURLData.'/create.html?case_style=115&amp;p=1&amp;ref=shopbyshop">Textures &amp; Patterns</a> </li>
<li> <a href="'.$this->baseURLData.'/create.html?case_style=57&amp;p=1&amp;ref=shopbyshop">Typography</a> </li>
<li> <a href="'.$this->baseURLData.'/create.html?case_style=336&amp;p=1&amp;ref=shopbyshop">Weapon</a> </li>
<li> <a href="'.$this->baseURLData.'/create.html?case_style=53&amp;p=1&amp;ref=shopbyshop">Zodiac</a> </li>

</ul>
</li>

<li><a href="#"><i class="mi mi-createOwn"></i>Create Your Own<span><i class="fa fa-plus"></i></span></a>
    <ul class="subNav level1">
	 <li><a href="#">Mobile Skins<span><i class="fa fa-plus"></i></span></a>
	  <ul class="subNav level2">';

// $cat = Mage::getModel('catalog/category')->load(75);
// $subcats = $cat->getChildren();
$parent = Mage::getModel('catalog/category')->load(75)->getChildren();

$subcats = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->setStoreId(Mage::app()->getStore()->getId())->addAttributeToSort('position', 'asc')->addIdFilter($parent)->load(); 


foreach($subcats as $subCatids)
{
	$subCatid = $subCatids->getId();
	
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
 
 $menuStr = $menuStr.'<li><a href="#">'.$name.'<span><i class="fa fa-plus"></i></span></a>
     <ul class="subNav level3">';
 	
	  
	// $sub_cat = Mage::getModel('catalog/category')->load($_category->getId());
    // $sub_subcats = $sub_cat->getChildren();
    
	$parent = Mage::getModel('catalog/category')->load($_category->getId())->getChildren();
$sub_subcats = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->setStoreId(Mage::app()->getStore()->getId())->addAttributeToSort('position', 'asc')->addIdFilter($parent)->load(); 
	
	foreach($sub_subcats as $sub_subCatids)
    {
		$sub_subCatid = $sub_subCatids->getId();
		
          $_sub_category = Mage::getModel('catalog/category')->load($sub_subCatid);
          if($_sub_category->getIsActive()) {
			  $suburl = $_sub_category->getURL();
			  $subname = $_sub_category->getName();
			  
			  
						$customize_thousnad_of_design_link = str_replace('create','shop',$_sub_category->getUrl()); 
						$_collection = $_sub_category->getProductCollection();
						$_product =  $_collection->getFirstItem(); // this will get first item in collection
						$blankPrdId = $_product->getId();

			  
			  
			   $menuStr = $menuStr.'<li><a href="'.$this->baseUrl.'designer3?id='.$blankPrdId.'&cat_id='.$_sub_category->getId().'">'.$subname.'</a>
</li>';
             
           }
     }
     $menuStr = $menuStr.'</li></ul>';
  }
}


   $menuStr = $menuStr.'

</ul>
</li>
	<li><a href="#">Mobile Cases<span><i class="fa fa-plus"></i></span></a>
	 <ul class="subNav level1">';
 
$parent = Mage::getModel('catalog/category')->load(66)->getChildren();

$subcats = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->setStoreId(Mage::app()->getStore()->getId())->addAttributeToSort('position', 'asc')->addIdFilter($parent)->load(); 


foreach($subcats as $subCatids)
{
	$subCatid = $subCatids->getId();
	
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
 
 $menuStr = $menuStr.'<li><a href="#">'.$name.'<span><i class="fa fa-plus"></i></span></a>
     <ul class="subNav level3">';
 	
	  
	// $sub_cat = Mage::getModel('catalog/category')->load($_category->getId());
    // $sub_subcats = $sub_cat->getChildren();
    
	$parent = Mage::getModel('catalog/category')->load($_category->getId())->getChildren();
$sub_subcats = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->setStoreId(Mage::app()->getStore()->getId())->addAttributeToSort('position', 'asc')->addIdFilter($parent)->load(); 
	
	foreach($sub_subcats as $sub_subCatids)
    {
		$sub_subCatid = $sub_subCatids->getId();
		
          $_sub_category = Mage::getModel('catalog/category')->load($sub_subCatid);
          if($_sub_category->getIsActive()) {
			  $suburl = $_sub_category->getURL();
			  $subname = $_sub_category->getName();
			  
			  
						$customize_thousnad_of_design_link = str_replace('create','shop',$_sub_category->getUrl()); 
						$_collection = $_sub_category->getProductCollection();
						$_product =  $_collection->getFirstItem(); // this will get first item in collection
						$blankPrdId = $_product->getId();

			  
			  
			   $menuStr = $menuStr.'<li><a href="'.$this->baseUrl.'designer3?id='.$blankPrdId.'&cat_id='.$_sub_category->getId().'">'.$subname.'</a>
</li>';
             
           }
     }
     $menuStr = $menuStr.'</li></ul>';
  }
}

$menuStr = $menuStr.'</ul>

</li>
  <li><a href="#">T-Shirts<span><i class="fa fa-plus"></i></span></a>
	<ul class="subNav level1">';
 

 
// $cat = Mage::getModel('catalog/category')->load(91);
// $subcats = $cat->getChildren();

$parent = Mage::getModel('catalog/category')->load(91)->getChildren();
$subcats = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->setStoreId(Mage::app()->getStore()->getId())->addAttributeToSort('position', 'asc')->addIdFilter($parent)->load();

foreach($subcats as $subCatids)
{
	$subCatid = $subCatids->getId();
	
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  str_replace('index.php/','',$_category->getURL());
	  $name = $_category->getName();
	  
						$customize_thousnad_of_design_link = str_replace('create','shop',$_category->getUrl()); 
						$_collection = $_category->getProductCollection();
						$_product =  $_collection->getFirstItem(); // this will get first item in collection
						$blankPrdId = $_product->getId();	  
	  
    $menuStr = $menuStr.'<li><a href="'.$this->baseUrl.'designer3?id='.$blankPrdId.'&cat_id='.$_category->getId().'">'.$name.'</a></li>';
	  
	
    
  }
}
 $menuStr = $menuStr.'</li></ul>';

 
$menuStr = $menuStr.'


	 <li><a href="#">LapTop Skins <span><i class="fa fa-plus"></i></span></a>
	 <ul class="subNav level1">';
 
// $cat = Mage::getModel('catalog/category')->load(84);
// $subcats = $cat->getChildren();

$parent = Mage::getModel('catalog/category')->load(84)->getChildren();
$subcats = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->setStoreId(Mage::app()->getStore()->getId())->addAttributeToSort('position', 'asc')->addIdFilter($parent)->load();

foreach($subcats as $subCatids)
{
	$subCatid = $subCatids->getId();
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  str_replace('index.php/','',$_category->getURL());
	  $name = $_category->getName();
						$customize_thousnad_of_design_link = str_replace('create','shop',$_category->getUrl()); 
						$_collection = $_category->getProductCollection();
						$_product =  $_collection->getFirstItem(); // this will get first item in collection
						$blankPrdId = $_product->getId();	  
 
   $menuStr = $menuStr.'<li><a href="'.$this->baseUrl.'designer3?id='.$blankPrdId.'&cat_id='.$_category->getId().'">'.$name.'<span></a>
</li>';
   
 	
	  
	
    
  }
}
 $menuStr = $menuStr.'</li></ul>';

$menuStr = $menuStr.'
<li><a href="#">Tablet Skins<span><i class="fa fa-plus"></i></span></a>
	 <ul class="subNav level1">';

// $cat = Mage::getModel('catalog/category')->load(532);
// $subcats = $cat->getChildren();

$parents = Mage::getModel('catalog/category')->load(532)->getChildren();
$subcats = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->setStoreId(Mage::app()->getStore()->getId())->addAttributeToSort('position', 'asc')->addIdFilter($parents)->load();

foreach($subcats as $subCatids)
{
	$subCatid = $subCatids->getId();
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();

	$parent = Mage::getModel('catalog/category')->load($_category->getId())->getChildren();
	$sub_subcats = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->setStoreId(Mage::app()->getStore()->getId())->addAttributeToSort('position', 'asc')->addIdFilter($parent)->load(); 
	  

if(count($sub_subcats) > 0){
 $menuStr = $menuStr.'<li><a href="#">'.$name.'<span><i class="fa fa-plus"></i></span></a>';	
 $menuStr = $menuStr.' <ul class="subNav level2">';
 
 
 
 foreach($sub_subcats as $sub_subCatids)
    {
		$sub_subCatid =  $sub_subCatids->getId(); 
		
		
          $_sub_category = Mage::getModel('catalog/category')->load($sub_subCatid);
          if($_sub_category->getIsActive()) {
			  $suburl = str_replace('index.php/','',$_sub_category->getURL());
			  $subname = $_sub_category->getName();
						$customize_thousnad_of_design_link = str_replace('create','shop',$_sub_category->getUrl()); 
						$_collection = $_sub_category->getProductCollection();
						$_product =  $_collection->getFirstItem(); // this will get first item in collection
						$blankPrdId = $_product->getId();	  
			   
 $menuStr = $menuStr.'<li><a href="'.$this->baseUrl.'designer3?id='.$blankPrdId.'&cat_id='.$_sub_category->getId().'"> '.$subname.'</a></li>';			  
	  
           }
     }
	 		  
  
$menuStr = $menuStr.'</ul>';  
}
else{
						$customize_thousnad_of_design_link = str_replace('create','shop',$_category->getUrl()); 
						$_collection = $_category->getProductCollection();
						$_product =  $_collection->getFirstItem(); // this will get first item in collection
						$blankPrdId = $_product->getId();	  
			   
 $menuStr = $menuStr.'<li><a href="'.$this->baseUrl.'designer3?id='.$blankPrdId.'&cat_id='.$_category->getId().'"> '.$name.'</a></li>';			  
	
	
}
$menuStr = $menuStr.'</li>';    
 	 
   
    
  }
}
$menuStr = $menuStr.'</ul></li>';
$menuStr = $menuStr.'
</ul>
</li>';
$menuStr = $menuStr.'
<li class="divider"></li>
  <li><a href="'.$this->baseURLData.'/sell.html"><i class="mi mi-sellArt"></i>Sell Your ArtWork</a></li>
  <li><a href="'.$this->baseURLData.'/gift/gift-certificate/gift-certificate.html"><i class="mi mi-buyGift"></i>Buy Gift Vouchers</a></li>
	<li><a href="'.$this->baseURLData.'/promotional_gift"><i class="mi mi-corporate"></i>Offers & Coupons</a></li> 
	<li><a href="'.$this->baseURLData.'/eform"><i class="mi mi-offersCoupons"></i>Corporate Orders</a></li>
  <li class="divider"></li>
</ul>';
return $menuStr;
}

public function checkSalesRuleIsApplicableOnProduct($productId,$ruleId)
	{	
		$pid = $productId;
		$_product= Mage::getModel('catalog/product')->load($pid);

		$coll = Mage::getResourceModel('salesrule/rule_collection')->addFieldToFilter('rule_id',array("in"=>array($ruleId)))->load();
	
		foreach($coll as $rule){
			$rule->afterLoad(); 
		}  

		$quoteId = Mage::getSingleton('checkout/session')->getQuoteId(); 
		$real_quote = Mage::getSingleton('sales/quote')->load($quoteId);                
		$product = Mage::getModel('sales/quote_item')->setQuote($real_quote)->setProduct($_product);  
		$product->setAllItems(array($_product));                 
		$product->getProduct()->setProductId($_product->getEntityId());   
		//$product = Mage::getModel('catalog/product')->load($pid); 
		foreach($coll as $rule) 
		{ 
			$applicable = (bool)$rule->getConditions()->validate($product);
		}
				
		return $applicable;
	}
	
	public function getProductFinalPriceInCart($itemCart){		
				
			$helper = Mage::helper('catalog/product_configuration');
			$options = $helper->getCustomOptions($itemCart);
			
			$item           = $itemCart;
			$product        = $item->getProduct();
			$price = 0;
			
			foreach($options as $optionsVal){
			
				$option = $product->getOptionById($optionsVal['option_id']);
				$itemOption = $item->getOptionByCode('option_' . $option->getId());
				foreach ($option->getValues() as $values) {
					if ($values->getId() == $itemOption['value']) {

							//echo $itemOption['value'];
							//echo '<br/>';					
							//echo $values->getId();
							//echo '<br/>';					
							//echo $values->price;
							//echo '<br/>';					
							
							$price = $price + $values->price;
					}
					
				}
				
				 		
			}
			
         return $price;			
		
	}
	
	public function getMatchSearchCategoryId($matchCatId){	

		$query = 'SELECT match_cat_child_id FROM a_search_matching_words WHERE match_word_db_id ="'.$matchCatId.'" and match_word_type="category" LIMIT 1';
		
		$match_cat_child_id = $this->dbRead->fetchOne($query);
			
		if($match_cat_child_id != ''){
			
			$match_cat_child_id_arr = explode(',',$match_cat_child_id);
			
			//self::$matchCatIdsSearch = array_merge(self::$matchCatIdsSearch, $match_cat_child_id_arr);
			
			//foreach($match_cat_child_id_arr as $match_cat_child_id_arr_val)
			 //$this->getMatchSearchCategoryId($match_cat_child_id_arr_val);			
			
		}
		else
					$match_cat_child_id_arr = array($matchCatId);
		 // 	self::$matchCatIdsSearch = array_merge(self::$matchCatIdsSearch, array($matchCatId));
		
		return array_unique($match_cat_child_id_arr);
	}
	
	
	public function is_mobile_bhishoom() {
			  $user_agent=strtolower(getenv('HTTP_USER_AGENT'));
			  $accept=strtolower(getenv('HTTP_ACCEPT'));
			 
			  if ((strpos($accept,'text/vnd.wap.wml')!==false) ||
				  (strpos($accept,'application/vnd.wap.xhtml+xml')!==false)) {
				return 1; // Мобильный браузер обнаружен по HTTP-заголовкам
			  }
			 
			  if (isset($_SERVER['HTTP_X_WAP_PROFILE']) ||
				  isset($_SERVER['HTTP_PROFILE'])) {
				return 2; // Мобильный браузер обнаружен по установкам сервера
			  }
			 
			  if (preg_match('/(mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|'.
				'wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|'.
				'lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|'.
				'mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|'.
				'm881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|'.
				'r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|'.
				'i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|'.
				'htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|'.
				'sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|'.
				'p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|'.
				'_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|'.
				's800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|'.
				'd736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |'.
				'sonyericsson|samsung|240x|x320vx10|nokia|sony cmd|motorola|'.
				'up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|'.
				'pocket|kindle|mobile|psp|treo|android|iphone|ipod|webos|wp7|wp8|'.
				'fennec|blackberry|htc_|opera m|windowsphone)/', $user_agent)) {
				return 3; // Мобильный браузер обнаружен по сигнатуре User Agent
			  }
			 
			  if (in_array(substr($user_agent,0,4),
				Array("1207", "3gso", "4thp", "501i", "502i", "503i", "504i", "505i", "506i",
					  "6310", "6590", "770s", "802s", "a wa", "abac", "acer", "acoo", "acs-",
					  "aiko", "airn", "alav", "alca", "alco", "amoi", "anex", "anny", "anyw",
					  "aptu", "arch", "argo", "aste", "asus", "attw", "au-m", "audi", "aur ",
					  "aus ", "avan", "beck", "bell", "benq", "bilb", "bird", "blac", "blaz",
					  "brew", "brvw", "bumb", "bw-n", "bw-u", "c55/", "capi", "ccwa", "cdm-",
					  "cell", "chtm", "cldc", "cmd-", "cond", "craw", "dait", "dall", "dang",
					  "dbte", "dc-s", "devi", "dica", "dmob", "doco", "dopo", "ds-d", "ds12",
					  "el49", "elai", "eml2", "emul", "eric", "erk0", "esl8", "ez40", "ez60",
					  "ez70", "ezos", "ezwa", "ezze", "fake", "fetc", "fly-", "fly_", "g-mo",
					  "g1 u", "g560", "gene", "gf-5", "go.w", "good", "grad", "grun", "haie",
					  "hcit", "hd-m", "hd-p", "hd-t", "hei-", "hiba", "hipt", "hita", "hp i",
					  "hpip", "hs-c", "htc ", "htc-", "htc_", "htca", "htcg", "htcp", "htcs",
					  "htct", "http", "huaw", "hutc", "i-20", "i-go", "i-ma", "i230", "iac",
					  "iac-", "iac/", "ibro", "idea", "ig01", "ikom", "im1k", "inno", "ipaq",
					  "iris", "jata", "java", "jbro", "jemu", "jigs", "kddi", "keji", "kgt",
					  "kgt/", "klon", "kpt ", "kwc-", "kyoc", "kyok", "leno", "lexi", "lg g",
					  "lg-a", "lg-b", "lg-c", "lg-d", "lg-f", "lg-g", "lg-k", "lg-l", "lg-m",
					  "lg-o", "lg-p", "lg-s", "lg-t", "lg-u", "lg-w", "lg/k", "lg/l", "lg/u",
					  "lg50", "lg54", "lge-", "lge/", "libw", "lynx", "m-cr", "m1-w", "m3ga",
					  "m50/", "mate", "maui", "maxo", "mc01", "mc21", "mcca", "medi", "merc",
					  "meri", "midp", "mio8", "mioa", "mits", "mmef", "mo01", "mo02", "mobi",
					  "mode", "modo", "mot ", "mot-", "moto", "motv", "mozz", "mt50", "mtp1",
					  "mtv ", "mwbp", "mywa", "n100", "n101", "n102", "n202", "n203", "n300",
					  "n302", "n500", "n502", "n505", "n700", "n701", "n710", "nec-", "nem-",
					  "neon", "netf", "newg", "newt", "nok6", "noki", "nzph", "o2 x", "o2-x",
					  "o2im", "opti", "opwv", "oran", "owg1", "p800", "palm", "pana", "pand",
					  "pant", "pdxg", "pg-1", "pg-2", "pg-3", "pg-6", "pg-8", "pg-c", "pg13",
					  "phil", "pire", "play", "pluc", "pn-2", "pock", "port", "pose", "prox",
					  "psio", "pt-g", "qa-a", "qc-2", "qc-3", "qc-5", "qc-7", "qc07", "qc12",
					  "qc21", "qc32", "qc60", "qci-", "qtek", "qwap", "r380", "r600", "raks",
					  "rim9", "rove", "rozo", "s55/", "sage", "sama", "samm", "sams", "sany",
					  "sava", "sc01", "sch-", "scoo", "scp-", "sdk/", "se47", "sec-", "sec0",
					  "sec1", "semc", "send", "seri", "sgh-", "shar", "sie-", "siem", "sk-0",
					  "sl45", "slid", "smal", "smar", "smb3", "smit", "smt5", "soft", "sony",
					  "sp01", "sph-", "spv ", "spv-", "sy01", "symb", "t-mo", "t218", "t250",
					  "t600", "t610", "t618", "tagt", "talk", "tcl-", "tdg-", "teli", "telm",
					  "tim-", "topl", "tosh", "treo", "ts70", "tsm-", "tsm3", "tsm5", "tx-9",
					  "up.b", "upg1", "upsi", "utst", "v400", "v750", "veri", "virg", "vite",
					  "vk-v", "vk40", "vk50", "vk52", "vk53", "vm40", "voda", "vulc", "vx52",
					  "vx53", "vx60", "vx61", "vx70", "vx80", "vx81", "vx83", "vx85", "vx98",
					  "w3c ", "w3c-", "wap-", "wapa", "wapi", "wapj", "wapm", "wapp", "wapr",
					  "waps", "wapt", "wapu", "wapv", "wapy", "webc", "whit", "wig ", "winc",
					  "winw", "wmlb", "wonu", "x700", "xda-", "xda2", "xdag", "yas-", "your",
					  "zeto", "zte-"))) {
				return 4; // Мобильный браузер обнаружен по сигнатуре User Agent
			  } 
				return false; // Мобильный браузер не обнаружен
}	
	


public function getFedexTrackingResponseByApi($trackingNumber,$shipMentId)
{
		$wsdlBasePath = Mage::getModuleDir('etc', 'Mage_Usa')  . DS . 'wsdl' . DS . 'FedEx' . DS;
		$path_to_wsdl = $wsdlBasePath . 'TrackService_v10.wsdl';

		ini_set("soap.wsdl_cache_enabled", "0");
		$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information
		$client->__setLocation(Mage::getStoreConfig('carriers/fedex/sandbox_mode')
            ? 'https://wsbeta.fedex.com:443/web-services'
            : 'https://ws.fedex.com:443/web-services'
        );

		$trackRequest = array(
            'WebAuthenticationDetail' => array(
				'ParentCredential' => array(
					'Key' => '', 
					'Password' => ''
				),			
                'UserCredential' => array(
                    'Key'      => Mage::getStoreConfig('carriers/fedex/key'), 
                    'Password' => Mage::getStoreConfig('carriers/fedex/password')
                )
            ),
            'ClientDetail' => array(
                'AccountNumber' => Mage::getStoreConfig('carriers/fedex/account'), 
                'MeterNumber'   => Mage::getStoreConfig('carriers/fedex/meter_number')
            ),
            'Version' => array(
                'ServiceId'    => 'trck',
                'Major'        => '10',
                'Intermediate' => '0',
                'Minor'        => '0'
            ),
            'SelectionDetails'=>array('PackageIdentifier' => array(
                'Type'  => 'TRACKING_NUMBER_OR_DOORTAG',
                'Value' => $trackingNumber,
            )),
            /*
             * 0 = summary data, one signle scan structure with the most recent scan
             * 1 = multiple sacn activity for each package
             */
            'ProcessingOptions' => "INCLUDE_DETAILED_SCANS",
            'TrackingNumber' => $trackingNumber,
            'SenderEMailAddress' => "noreply@bhishoom.com",
            'SenderContactName' => "Bhishoom",
            'NotificationDetail' => array(
											'PersonalMessage' => $this->getDeliveryEmailTemplate($shipMentId),
											'Recipients' => array(
												'0' => array(
													'EMailNotificationRecipientType' => 'RECIPIENT',
													'EMailAddress' => $customerEmailAddress,
													//'NotificationEventsRequested' => array('ON_DELIVERY', 'ON_EXCEPTION', 'ON_SHIPMENT'),
													'NotificationEventsRequested' => array('ON_DELIVERY'),
													'Format' => 'HTML'
												)
											)
										),
			);	
	
	
		$response = $client->track($trackRequest);
}

public function getFedexTrackingResponse($trackingNumber,$shipMentId)
{
	
		$shipMent_Track_collection = Mage::getResourceModel('sales/order_shipment_track_collection')->addAttributeToSelect('latest_track_info')->addAttributeToFilter('track_number',$trackingNumber);
		
		
		return $shipMent_Track_collection;
		
}

public function getDeliveryEmailTemplate($shipMentId,$getProcessedTemplate=true,$sendEmail=false)
	{		
		$shipment = Mage::getModel('sales/order_shipment')->load($shipMentId);
		$shipMent_Item_collection=Mage::getResourceModel('sales/order_shipment_item_collection')->addAttributeToSelect('*')->addAttributeToFilter('parent_id',$shipMentId);
			/*echo '<pre>';			
			  print_r($shipMent_Item_collection->getData());
			  exit;*/
			$chk = array();
			$oid = $shipment->getData('order_id');
			$chk = $_POST['chk'];
			$time = Mage::getModel('core/date')->date('H:i:s');
			$delivery_date = $_POST['delivery_date'];
			
			if(!$_POST['delivery_date'])
			$delivery_date = date('Y-m-d');
			$delivery_date =  date('l, F d, Y',strtotime ($delivery_date));
			//$delivery_date = explode('-',$delivery_date);
			//$delivery_date = $delivery_date[1].' '.$delivery_date[0].', '.$delivery_date[2];
			//$order = Mage::getModel('sales/order')->loadByIncrementId($oid);
			$order = Mage::getModel('sales/order')->load($oid);
			
			$orderIncrementId = $order->getIncrementId();
			
			$customername = $order->getCustomerName();
			$shipping_address = $order->getShippingAddress();
			//$prefix = $shipping_address->getPrefix();
			$fname = $shipping_address->getFirstname();
			$lname = $shipping_address->getLastname();
			$street = $shipping_address->getStreetFull();
			$city = $shipping_address->getCity();
			$region = $shipping_address->getRegion();
			$phone = $shipping_address->getTelephone(); 
			$post = $shipping_address->getPostcode(); 
			$country_id = $shipping_address->getCountry_id();
			$country = Mage::getModel('directory/country')->loadByCode($country_id);
			$country_name = $country->getName();
			$ship_address = '<strong>'.$prefix.' '.$fname. ' '.$lname.' </strong><br>'.$street.'<br>'.$city.', '.$region.', '.$post.
			'<br>'.$country_name.'<br>'.'T '.$phone;
			$ordered_total = $order->getBaseGrandTotal();
			$ordered_items = $order->getAllItems();
			$i=1;$order_detail = '';$total = 0;
			
			
			$smsItemName = '';
			$smsItemNameComma = '';			
			
			$order_detail .= '
               <tr>
        <td class="inner" style="padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;" ><table width="100%" border="0" class="tableborder" cellspacing="5" cellpadding="0" style="border-spacing:0;font-family:sans-serif;color:#333333;border-width:1px;border-style:solid;border-color:#A6A6A6;border-top-width:0px;border-left-width:0px;" >
		<tbody>
              <tr style="border-width:1px;border-style:solid;border-color:#A6A6A6;border-top-width:0px;border-left-width:0px;" >
                <td bgcolor="#c71b1b" style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;border-width:1px;border-style:solid;border-color:#A6A6A6;border-right-width:0px;border-bottom-width:0px;" ><p class="tableHead oneWord" style="Margin:0;line-height:1.5;font-weight:bold;padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;font-size:12px;color:#fff;background-color:#c71b1b;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;text-align:center;white-space:nowrap;" >S. No.</p></td>
				 <td bgcolor="#c71b1b" style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;border-width:1px;border-style:solid;border-color:#A6A6A6;border-right-width:0px;border-bottom-width:0px;" ><p class="tableHead" style="Margin:0;line-height:1.5;font-weight:bold;padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;font-size:12px;color:#fff;background-color:#c71b1b;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;text-align:center;" >ITEM DETAILS</p></td>  
                  <td  bgcolor="#c71b1b" style="max-width:150px;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;border-width:1px;border-style:solid;border-color:#A6A6A6;border-right-width:0px;border-bottom-width:0px;" ><p class="tableHead" style="Margin:0;line-height:1.5;font-weight:bold;padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;font-size:12px;color:#fff;background-color:#c71b1b;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;text-align:center;" >QTY.</p></td>				 
				</tr>';			                
			foreach ($shipMent_Item_collection as $shipMent_Item) 
			{
				//$itemId = $items->getId();
				 				
					$itemName = $shipMent_Item['name'];
					//$itemSku = $items->getSku();
		    		//$itemPrice = $items->getPrice();
		    		$itemQty = $shipMent_Item['qty'];
		    		//$itemSubTotal = $items->getRowTotal();
		    		//$total += $itemSubTotal;
		    		//$finelName = $itemName;
					
		    		//$itemOptions = $items->getProductOptions();	
		    		/*if($itemOptions)
		    		{
		    			$op = $itemOptions['options'];        		
		        		foreach ($op as $options) 
		        		{
		        			$optionLabel = $options['label'];
		        			if($optionLabel == 'Product Detail')
		        			{
			        			$optionVal = $options['value'];
			        			$val = explode('/', $optionVal);
			        			$finelName = $val[1].' '.$itemName;
			        		}       		
						}
					}*/
					$order_detail .= '<tr style="border-width:1px;border-style:solid;border-color:#A6A6A6;border-top-width:0px;border-left-width:0px;" ><td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;border-width:1px;border-style:solid;border-color:#A6A6A6;border-right-width:0px;border-bottom-width:0px;" ><p class="tableData center" style="Margin:0;color:#000;line-height:1.5;padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;font-size:12px;text-align:center;" >'.$i.'</p></td>
					<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;border-width:1px;border-style:solid;border-color:#A6A6A6;border-right-width:0px;border-bottom-width:0px;" ><p class="tableData" style="Margin:0;color:#000;text-align:left;line-height:1.5;padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;font-size:12px;" >'.$itemName.'</p></td>
					<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;border-width:1px;border-style:solid;border-color:#A6A6A6;border-right-width:0px;border-bottom-width:0px;" ><p class="tableData center" style="Margin:0;color:#000;line-height:1.5;padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;font-size:12px;text-align:center;" >'.$itemQty*1 .'</p></td>
              </tr>';              	
					                  	

				$smsItemName = $smsItemName.$smsItemNameComma.'"'.str_replace(' ','+',trim($itemName)).'"';
				$smsItemNameComma = ',+';
				$i++;
			}
			$shipping_amount = $order->getShippingAmount();
			$total += $shipping_amount;
			$order_detail .= '</tbody>
          </table></td>';
		  
		  
		  
				//echo '<fieldset><address>'.$prefix.' '.$fname. ' '.$lname.'<br>'.$street.'<br>'.$city.',  '.$region.', '.$post.'<br>'.$country_name.'<br>T : '.$phone.'</address></fieldset>';
				$senderName =  Mage::getStoreConfig('trans_email/ident_general/name');
				$senderEmail = Mage::getStoreConfig('trans_email/ident_general/email');
				
				
if($order->getCustomerId() === NULL)
{
$fname = $order->getBillingAddress()->getName();
$email = $order->getBillingAddress()->getEmail();
}
else
{
$customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
$fname = $order->getCustomerName();
$email = $order->getCustomerEmail();
}				
				
				//$email = $order->getCustomerEmail();
	 	 	
				$emailTemplate = Mage::getModel('core/email_template')->loadDefault('order_email_template');
				$emailTemplateVariables = array();
				$emailTemplateVariables['name'] = $fname;
				$emailTemplateVariables['order_id'] = $orderIncrementId;	
				$emailTemplateVariables['delivery_date'] = $delivery_date;	
				$emailTemplateVariables['ship_address'] = $ship_address;	
				$emailTemplateVariables['order_detail'] = $order_detail;
				$emailTemplateVariables['customername'] = $customername;
				$emailTemplateVariables['fname'] = $fname;
				$emailTemplate->setSenderName($senderName);
	            $emailTemplate->setSenderEmail($senderEmail);
	            $emailTemplate->setType('html');	

				if($getProcessedTemplate==true){	
				$processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);				
				return $processedTemplate;		
				}
				else{
					$emailTemplate->setTemplateSubject('Your Bhishoom.com order (#'.$orderIncrementId.') delivery confirmation');
					//$email = 'yadavlokesh583@gmail.com';
					//$fname = $order->getCustomerFirstName();
					$emailTemplate->send($email, $fname, $emailTemplateVariables);		
				}	
	            //$emailTemplate->setTemplateSubject('Your Bhishoom.com order (#'.$orderIncrementId.') delivery confirmation');
	            //$emailTemplate->send($email, $fname, $emailTemplateVariables);		
				//Mage::getSingleton('core/session')->addSuccess(Mage::helper('order')->__('Mail Sent.'));		 		
	}


 public function checkEmailExists($emailAddress)
    {
        $emailAddressCheck = Mage::getModel('customer/customer')
            ->getCollection()
            ->addAttributeToSelect('email')
            ->addAttributeToFilter('email', $emailAddress)->load();
 
        if(!$emailAddressCheck->getSize()) {
            $result = 'ok';
        } else {
            $result = 'error';
        }
 
        return $result;
    }

 public function getOrderData($orderId){
		$write = Mage::helper('function/more')->getDbWriteObject();
		$read = Mage::helper('function/more')->getDbReadObject();
		$retVal = true;
		
			foreach($itemDetailsAll as $itemDetailsOrder){
				
				if($itemDetailsOrder["nameSuffix"] == "" || $itemDetailsOrder["nameSuffix"] == 0){
				$retVal = false;
				break;
				}
			}
 
				return $retVal;
 }



	public function update_stock($sxml, $code, $color_id, $color_name, $size_id, $size_name, $cat_id)
	{		
		$write = Mage::getSingleton( 'core/resource' )->getConnection('core_write');
		foreach ($sxml as $data) 
		{
			foreach ($data->style_id as $datastyle)
			{
				if($datastyle == $code)
				{
					foreach ($data->style_color as $datacolor) 
					{
						if($datacolor->style_color_name == $color_name)
						{
							foreach ($datacolor->style_size as $datasize) 
							{
								if($datasize->size_name == $size_name)
								{
									$main_stock = $datasize->size_stock;
									$prod_collection = Mage::getModel('catalog/category')->load($cat_id);
									$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*');
									foreach($collection as $product) 
									{
										if($product->isConfigurable())
										{
											$prod_id = $product->getId();
											$conf = Mage::getModel('catalog/product_type_configurable')->setProduct($product);
											$simple_collection = $conf->getUsedProductCollection()->addAttributeToSelect('*');
											$simple_collection->addAttributeToFilter('color', $color_id)->addAttributeToFilter('Size', $size_id);
											foreach($simple_collection as $simple_product)
											{
												$id = $simple_product->getId();
												$write->query("Update `cataloginventory_stock_item` SET `qty` = ".$main_stock." WHERE `product_id` = ".$id);
												$write->query("Update `cataloginventory_stock_status` SET `qty` = ".$main_stock." WHERE `product_id` = ".$id);
												$write->query("Update `cataloginventory_stock_status_idx` SET `qty` = ".$main_stock." WHERE `product_id` = ".$id);
											}
										}			
									}
								}
							}
						}
					}
				}
			}
		}
	}
 
	public function checkInventoryByCategory($catId){
		
		$_category = Mage::getModel('catalog/category')->load($catId);
		return $_category->getCategoryNumStockItem();
		
	}
	
	
	public function checkCashOnDeliveryIsValid(){
		
			$min_order_total = Mage::getStoreConfig('payment/phoenix_cashondelivery/min_order_total');
			$max_order_total = Mage::getStoreConfig('payment/phoenix_cashondelivery/max_order_total');
			
			$cartTotalProductOnly = Mage::helper('function')->getCartItemTotalProductsOnly();
			
			if($cartTotalProductOnly >= $min_order_total && $cartTotalProductOnly <= $max_order_total)
				return true;
			else
				return false;
		
	}
	
}

?>