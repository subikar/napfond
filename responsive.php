<?php 
ini_set('display_errors', 1);
ini_set("memory_limit","1500M");
ini_set('max_execution_time', 18000);
//ini_set('display_errors', 0);
error_reporting(E_ALL);
include_once('app/Mage.php');
umask(0);
Mage::app('');
Mage::setIsDeveloperMode(true);
$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' ); // To read from the database
$write = Mage::getSingleton( 'core/resource' )->getConnection( 'core_write' ); // To write to the database
/*$categoryId = 3;    
$products = Mage::getSingleton('catalog/category')->load($categoryId)->getProductCollection();
 foreach($products as $product){
	$productid =  $product->getId();
	 
	 $productt = Mage::getModel('catalog/product')->load($productid);
	 
	 echo $productt->getUrlPath()."<br>";
	 
}*/

?>
<ul class="mainNav navGroup1">
<li><a href="#"><i class="mi mi-mobileCase"></i>Mobile Cases<span><i class="fa fa-plus"></i></span></a>
<ul class="subNav level1">
<?php
$cat = Mage::getModel('catalog/category')->load(3);
$subcats = $cat->getChildren();

foreach(explode(',',$subcats) as $subCatid)
{
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
	?>
     <li><a href="<?php echo $url; ?>"><?php echo $name;?><span><i class="fa fa-plus"></i></span></a>
	 <ul class="subNav level2">
   <?php	
	  
	$sub_cat = Mage::getModel('catalog/category')->load($_category->getId());
    $sub_subcats = $sub_cat->getChildren();
    foreach(explode(',',$sub_subcats) as $sub_subCatid)
    {
          $_sub_category = Mage::getModel('catalog/category')->load($sub_subCatid);
          if($_sub_category->getIsActive()) {
			  $suburl = $_sub_category->getURL();
			  $subname = $_sub_category->getName();
			  ?>
			   <li><a href="<?php echo $suburl; ?>"><?php echo $subname; ?></a></li>
		
             <?php 
             
           }
     }
	 ?>
	 </ul>
</li>
<?php    
  }
}


?>

</ul>
</li>

<li><a href="#"><i class="mi mi-mobileSkin"></i>Mobile Skin<span><i class="fa fa-plus"></i></span></a>
<ul class="subNav level1">
<?php
$cat = Mage::getModel('catalog/category')->load(34);
$subcats = $cat->getChildren();

foreach(explode(',',$subcats) as $subCatid)
{
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
	?>
    <li><a href="<?php echo $url; ?>"><?php echo $name;?><span><i class="fa fa-plus"></i></span></a>
     <ul class="subNav level2">
   <?php	
	$parent = Mage::getModel('catalog/category')->load($_category->getId())->getChildren();
	$sub_subcats = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->setStoreId(Mage::app()->getStore()->getId())->addAttributeToSort('position', 'asc')->addIdFilter($parent)->load();  
	
    foreach($sub_subcats as $sub_subCatids)
    {
		$sub_subCatid =  $sub_subCatids->getId(); 
		
		
          $_sub_category = Mage::getModel('catalog/category')->load($sub_subCatid);
          if($_sub_category->getIsActive()) {
			  $suburl = $_sub_category->getURL();
			  $subname = $_sub_category->getName();
			  ?>
			   <li><a href="<?php echo $suburl; ?>"><?php echo $subname; ?></a>
</li>
			  
             <?php 
             
           }
     }
	 ?>
    </li></ul>
  <?php
  }
}


?>

</ul>
</li>

<li><a href="#"><i class="mi mi-tshirt"></i>T-Shirts<span><i class="fa fa-plus"></i></span></a>
<ul class="subNav level1">
<?php
$cat = Mage::getModel('catalog/category')->load(4);
$subcats = $cat->getChildren();

foreach(explode(',',$subcats) as $subCatid)
{
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
	?>
    <li><a href="<?php echo $url; ?>"><?php echo $name;?></a></li>
     
   <?php	
	
  }
}
 ?>
    </li></ul>
  <?php

?>

<li><a href="#"><i class="mi mi-laptop"></i>LapTop Skins<span><i class="fa fa-plus"></i></span></a>
<ul class="subNav level1">
<?php
// $cat = Mage::getModel('catalog/category')->load(8);
// $subcats = $cat->getChildren();
$parent = Mage::getModel('catalog/category')->load(8)->getChildren();
$subcats = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->setStoreId(Mage::app()->getStore()->getId())->addAttributeToSort('position', 'asc')->addIdFilter($parent)->load(); 
foreach($subcats as $subCatids)
{
	 $subCatid = $subCatids->getId();
	
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
	?>
    <li><a href="<?php echo $url; ?>"><?php echo $name;?></a>
</li>
     
   <?php	
	  
	
    
  }
}
 ?>
    </li></ul>
  <?php

?>


<li><a href="#"><i class="mi mi-mobileSkin"></i>Tablet Skin<span><i class="fa fa-plus"></i></span></a>
<ul class="subNav level1">
<?php
$parents = Mage::getModel('catalog/category')->load(478)->getChildren();
	$subcats = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->setStoreId(Mage::app()->getStore()->getId())->addAttributeToSort('position', 'asc')->addIdFilter($parents)->load();

foreach($subcats as $subCatids)
{
	$subCatid = $subCatids->getId();
	
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
	?>
    <li><a href="<?php echo $url; ?>"><?php echo $name;?><span><i class="fa fa-plus"></i></span></a>
     <ul class="subNav level2">
   <?php	
	$parent = Mage::getModel('catalog/category')->load($_category->getId())->getChildren();
	$sub_subcats = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->setStoreId(Mage::app()->getStore()->getId())->addAttributeToSort('position', 'asc')->addIdFilter($parent)->load();  
	
    foreach($sub_subcats as $sub_subCatids)
    {
		$sub_subCatid =  $sub_subCatids->getId(); 
		
		
          $_sub_category = Mage::getModel('catalog/category')->load($sub_subCatid);
          if($_sub_category->getIsActive()) {
			  $suburl = $_sub_category->getURL();
			  $subname = $_sub_category->getName();
			  ?>
			   <li><a href="<?php echo $suburl; ?>"><?php echo $subname; ?></a>
</li>
			  
             <?php 
             
           }
     }
	 ?>
    </li></ul>
  <?php
  }
}


?>

</ul>
</li>
<li><a href="#"><i class="mi mi-createOwn"></i>Create Your Own<span><i class="fa fa-plus"></i></span></a>
    <ul class="subNav level1">
	 <li><a href="#">Mobile Cases<span><i class="fa fa-plus"></i></span></a>
	  <ul class="subNav level2">
<?php
$cat = Mage::getModel('catalog/category')->load(66);
$subcats = $cat->getChildren();

foreach(explode(',',$subcats) as $subCatid)
{
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
	?>
    <li><a href="<?php echo $url; ?>"><?php echo $name;?><span><i class="fa fa-plus"></i></span></a>
     <ul class="subNav level3">
   <?php	
	  
	$sub_cat = Mage::getModel('catalog/category')->load($_category->getId());
    $sub_subcats = $sub_cat->getChildren();
    foreach(explode(',',$sub_subcats) as $sub_subCatid)
    {
          $_sub_category = Mage::getModel('catalog/category')->load($sub_subCatid);
          if($_sub_category->getIsActive()) {
			  $suburl = $_sub_category->getURL();
			  $subname = $_sub_category->getName();
			  ?>
			   <li><a href="<?php echo $suburl; ?>"><?php echo $subname; ?></a>
</li>
			   
             <?php 
             
           }
     }
     ?>
    </li></ul>
  <?php
  }
}


?>

</ul>
</li>
	<li><a href="#">Mobile Skin<span><i class="fa fa-plus"></i></span></a>
	 <ul class="subNav level1">
<?php
$cat = Mage::getModel('catalog/category')->load(75);
$subcats = $cat->getChildren();

foreach(explode(',',$subcats) as $subCatid)
{
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
	?>
    <li><a href="<?php echo $url; ?>"><?php echo $name;?><span><i class="fa fa-plus"></i></span></a>
     <ul class="subNav level2">
   <?php	
	  
	$sub_cat = Mage::getModel('catalog/category')->load($_category->getId());
    $sub_subcats = $sub_cat->getChildren();
    foreach(explode(',',$sub_subcats) as $sub_subCatid)
    {
          $_sub_category = Mage::getModel('catalog/category')->load($sub_subCatid);
          if($_sub_category->getIsActive()) {
			  $suburl = $_sub_category->getURL();
			  $subname = $_sub_category->getName();
			  ?>
			   <li><a href="<?php echo $suburl; ?>"><?php echo $subname; ?></a>
</li>
			  
             <?php 
             
           }
     }
     ?>
    </li></ul>
  <?php
  }
}


?>

</ul>
</li>
  <li><a href="#">T-Shirts<span><i class="fa fa-plus"></i></span></a>
	<ul class="subNav level1">
<?php
$cat = Mage::getModel('catalog/category')->load(91);
$subcats = $cat->getChildren();

foreach(explode(',',$subcats) as $subCatid)
{
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
	?>
    <li><a href="<?php echo $url; ?>"><?php echo $name;?><span><i class="fa fa-plus"></i></span></a>
</li>

     
   <?php	
	  
	
    
  }
}
 ?>
    </li></ul>
  <?php

?>


	 <li><a href="#">LapTop Skins <span><i class="fa fa-plus"></i></span></a>
	 <ul class="subNav level1">
<?php
$cat = Mage::getModel('catalog/category')->load(84);
$subcats = $cat->getChildren();

foreach(explode(',',$subcats) as $subCatid)
{
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
	?>
    <li><a href="<?php echo $url; ?>"><?php echo $name;?><span><i class="fa fa-plus"></i></span></a>
</li>
   
   <?php	
	  
	
    
  }
}
 ?>
    </li></ul>
  <?php

?>



	 <li><a href="#">Tablet Skins<span><i class="fa fa-plus"></i></span></a>
	 <ul class="subNav level1">
<?php
$cat = Mage::getModel('catalog/category')->load(532);
$subcats = $cat->getChildren();

foreach(explode(',',$subcats) as $subCatid)
{
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
	?>
    <li><a href="<?php echo $url; ?>"><?php echo $name;?><span><i class="fa fa-plus"></i></span></a>
</li>
     
   <?php	
	  
	
   
    
  }
}
 ?>
    </li></ul>
  <?php

?>

</ul>
</li>
<li class="divider"></li>
  <li><a href="#"><i class="mi mi-sellArt"></i>Sell Your ArtWork</a></li>
  <li><a href="#"><i class="mi mi-buyGift"></i>Buy Gift Vouchers</a></li>
  <li><a href="#"><i class="mi mi-offersCoupons"></i>Offers & Coupons</a></li>
  <li><a href="#"><i class="mi mi-corporate"></i>Corporate Orders</a></li>
  <li class="divider"></li>
  <li><a href="<?php echo Mage::getUrl('customer/account/login');?>"><i class="mi mi-Login"></i>Login</a></li>
  <li><a href="#"><i class="mi mi-faqs"></i>FAQ's</a></li>
  <li><a href="<?php echo Mage::helper('checkout/cart')->getCartUrl(); ?>"><i class="mi mi-orderStatus"></i>Order Status</a></li>
  <li><a href="#" ><i class="mi mi-contactUs"></i>Contact Us</a></li>
</ul>
      

  