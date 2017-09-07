<?php set_time_limit(0);
require_once('app/Mage.php');
umask(0);
Mage::app();
 
$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' );
$write = Mage::getSingleton( 'core/resource' )->getConnection( 'core_write' );


/* update skin custom option price */
$price = $read->query("SELECT option_type_price_id FROM catalog_product_option_type_price WHERE price = '-91'");
$op = $price->fetchAll();
foreach($op as $val)
{
	$op_id = $val['option_type_price_id'];
	$read->query("UPDATE catalog_product_option_type_price SET price = '-100' WHERE option_type_price_id = ".$op_id);
}


/*****Product by category *********************/

//$catNum = 191; //The number of the category you want to load

//$category = Mage::getModel('catalog/category')->load($catNum);

//$products = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*')->addCategoryFilter($category);

/*
foreach ($products as $product) {
    if (!$product->hasImage()) continue;
	
     
		$product->setSmallImage($product->getImage());
			 
	 
      $product->setThumbnail($product->getImage());
    $product->save();
}*/

/**********************************************/ 
/*
$skuStr = 'mobile-skin-case-main-virgo-hoon,mobile-skin-case-virgo-zodiac,mobile-skin-case-main-libra-hoon,mobile-skin-case-libra-zodiac,mobile-skin-case-dhan-lakshmi,mobile-skin-case-ra-one,mobile-skin-case-main-scorpio-hoon,mobile-skin-case-scorpio-zodiac,mobile-skin-case-virat-kohli-2,mobile-skin-case-main-leo-hoon,mobile-skin-case-leo-zodiac,mobile-skin-case-bholenath,mobile-skin-case-main-taurus-hoon,mobile-skin-case-taurus-zodiac,mobile-skin-case-main-sagittarius-hoon,mobile-skin-case-bal-hanuman,mobile-skin-case-dimaag-ka-dahi,mobile-skin-case-two-avatars,mobile-skin-case-aquarius-zodiac,mobile-skin-case-capricorn,mobile-skin-case-capricorn-zodiac,mobile-skin-case-goddess-lakshmi,mobile-skin-case-aquarius,mobile-skin-case-ek-dum-jhakkas,mobile-skin-case-gemini,mobile-skin-case-gemini-zodiac,mobile-skin-case-shree-krishna,mobile-skin-case-gabbar-ka-phone,mobile-skin-case-bheja-fry,mobile-skin-case-lord-vishnu,mobile-skin-case-devi-parvati-,mobile-skin-case-lord-brahma,mobile-skin-case-saraswati-devi,mobile-skin-case-bol-bachchan,mobile-skin-case-fish,mobile-skin-case-queen-of-water,mobile-skin-case-battery-charge,mobile-skin-case-crime-master,mobile-skin-case-aries,mobile-skin-case-aries-zodiac,mobile-skin-case-cancer,mobile-skin-case-cancer-zodiac';

$skuStr = explode(',',$skuStr);

foreach($skuStr as $skuStrVal){
 
$skuStrVal = trim($skuStrVal); 
 
$product = Mage::getModel('catalog/product')->loadByAttribute('sku', $skuStrVal);
 

    if (!$product->hasImage()) continue;
	
     
		$product->setSmallImage($product->getImage());
			 
	 
      $product->setThumbnail($product->getImage());
    $product->save();


}
*/
/*$write->query("UPDATE eav_attribute SET entity_type_id = '4', attribute_model = NULL, backend_model = 'eav/entity_attribute_backend_array', backend_type = 'varchar', backend_table = NULL, frontend_model = NULL, frontend_input = 'multiselect', frontend_class = NULL WHERE attribute_id = '137'");

$write->query("INSERT INTO catalog_product_entity_varchar (entity_type_id, attribute_id, store_id, entity_id, value) SELECT entity_type_id, attribute_id, store_id, entity_id, value FROM catalog_product_entity_int WHERE attribute_id = 137");

$write->query("DELETE FROM catalog_product_entity_int WHERE entity_type_id = 4 and attribute_id = 137");



/*$body = "Hi there, here is some plaintext body content";
$mail = Mage::getModel('core/email');
$mail->setToName('Hunny Bohara');
$mail->setToEmail('gravitainfotech@gmail.com');
$mail->setBody($body);
$mail->setSubject('The Subject');
$mail->setFromEmail('hunny.bohara@gravitainfotech.com');
$mail->setFromName("Hunny");
$mail->setType('text');// You can use 'html' or 'text'

try {
    $mail->send();
    Mage::getSingleton('core/session')->addSuccess('Your request has been sent');
    //$this->_redirect('');
}
catch (Exception $e) {
    Mage::getSingleton('core/session')->addError('Unable to send.');
    //$this->_redirect('');
}*/

//mail("gravitainfotech@gmail.com", "test", "Hello This is test mail");


?>