<?php set_time_limit(0);
ini_set('memory_limit','512M');
require_once('app/Mage.php');
umask(0);
Mage::app();

$categoryIds = array(34);//category id

$collection = Mage::getModel('catalog/product')
                             ->getCollection()->addAttributeToSelect(array('sku'));

?>

<?php $_collectionSize = $collection->count() ?>
    <?php //$_columnCount = $this->getColumnCount(); ?>
    <?php $i=0; foreach ($collection as $_product): ?> 
	
	<?php
	if(!in_array(34,$_product->getCategoryIds()))
	continue;
	?>	
<?php
 $productCollection = Mage::getModel("catalog/product")->load($_product->getId()); 
 $i = 1; 
 $getOptionsArray = $productCollection->getOptions();
 $show = true;
 
 foreach ($getOptionsArray as $value) { 
  
 if( strtolower(trim($value->getTitle())) == 'product detail')
	 $show = false;
 $i++; 
 }  
?>	
	
<?php if($show == true){?>
	<?php echo $_product->getId() ?> - <?php echo $_product->getName() ?> - <?php echo $_product->getSku() ?>
	<br/>
<?php }?>	
	
	
        <?php endforeach ?>