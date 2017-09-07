<?php
$installer = $this;
$installer->startSetup();
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('sellerdetail')};
CREATE TABLE {$this->getTable('sellerdetail')} (
  `sid` int(11) unsigned NOT NULL auto_increment,
  `customerid` int(11) default NULL,
  `link` varchar(255) default NULL, 
  `active` int(11) unsigned default NULL,
  `position` int(11) unsigned default NULL,
  `created_dt` datetime default NULL,
  `updated_dt` datetime default NULL,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup(); 