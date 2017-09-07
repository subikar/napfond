<?php
$installer = $this;
$installer->startSetup();

$sql = <<<SQLTEXT
CREATE TABLE `{$this->getTable('gomage_social_entity')}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(3) NOT NULL DEFAULT '0',
  `customer_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL DEFAULT '0',
  `social_id` varchar(255) DEFAULT NULL,  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;	
SQLTEXT;

$installer->run($sql);

$installer->endSetup();
	 