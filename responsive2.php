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



			file_put_contents(Mage::getBaseDir('media')."/menu_cache/mobile_menu_bhishoom.txt", Mage::helper('function/more')->getMobileMenuBhishoom());
	
	
			file_put_contents(Mage::getBaseDir('media')."/menu_cache/mobile_menu_bhishoom.txt", php_strip_whitespace (Mage::getBaseDir('media')."/menu_cache/mobile_menu_bhishoom.txt"));


?>      

  