<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table mksrotatedegree(id int not null auto_increment, productid varchar(255),  productimage varchar(255),  imagename varchar(255),   imageorder varchar(255), status varchar(100), primary key(id));

SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 