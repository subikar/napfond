<?php
include_once('app/Mage.php');
Mage::app();
$refererUrl = Mage::app()->getRequest()->getServer('HTTP_REFERER');
//if(!strpos($refererUrl,$_SERVER['HTTP_HOST']))
//exit;
$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' );
$write = Mage::getSingleton( 'core/resource' )->getConnection( 'core_write' );

$quote=Mage::getModel('checkout/cart')->getQuote();
$quoteData=$quote->getData();
echo '<pre>';
print_r($quoteData);
?>