<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('trendingproduct')};
CREATE TABLE {$this->getTable('trendingproduct')} (
  `tid` int(11) unsigned NOT NULL auto_increment,
  `style_id` int(11) unsigned default NULL,
  `pname` varchar(255) default NULL,
  `pimg` varchar(255) default NULL,
  `plink` varchar(255) default NULL,  
  `active` int(11) unsigned default NULL,
  `position` int(11) unsigned default NULL,
  `created_dt` datetime default NULL,
  `updated_dt` datetime default NULL,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


");

$installer->endSetup(); 