<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('faq')};
CREATE TABLE {$this->getTable('faq')} (
  `faq_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `category_id` int(11) unsigned NOT NULL default '0',
  `description` text NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  `ordering` int(11) unsigned NOT NULL default '0',
  `url_key` varchar(255) NOT NULL default '',
  `most_frequently` smallint(6) NOT NULL default '0',
  PRIMARY KEY (`faq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('faq_category')};
CREATE TABLE {$this->getTable('faq_category')} (
  `category_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `url_key` varchar(255) NOT NULL default '',
  `description` text NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',  
  `ordering` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('faq_store')};
CREATE TABLE {$this->getTable('faq_store')} (
  `faq_store_id` int(11) unsigned NOT NULL auto_increment,
  `faq_id` int(11) unsigned NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  `is_applied` tinyint(1) default '0',
  `title` varchar(255) default'',
  `category_id` int(11) unsigned NOT NULL default '0',
  `description` text NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  `ordering` int(11) unsigned NOT NULL default '0',
  `url_key` varchar(255) NOT NULL default '',
  `most_frequently` smallint(6) NOT NULL default '0',
  INDEX(`faq_id`),
  INDEX(`store_id`),
  UNIQUE(`faq_id`, `store_id`),
  FOREIGN KEY (`faq_id`) REFERENCES {$this->getTable('faq')} (`faq_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`store_id`) REFERENCES {$this->getTable('core/store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`faq_store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS {$this->getTable('faq_category_store')};
CREATE TABLE {$this->getTable('faq_category_store')} (
  `category_store_id` int(11) unsigned NOT NULL auto_increment,
  `category_id` int(11) unsigned NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  `is_applied` tinyint(1) default '0',  
  `name` varchar(255) NOT NULL default '',
  `url_key` varchar(255) NOT NULL default '',
  `description` text NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',  
  `ordering` int(11) unsigned NOT NULL default '0',
  INDEX(`category_id`),
  INDEX(`store_id`),
  UNIQUE(`category_id`, `store_id`),
  FOREIGN KEY (`category_id`) REFERENCES {$this->getTable('faq_category')} (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`store_id`) REFERENCES {$this->getTable('core/store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,  
  PRIMARY KEY (`category_store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup(); 