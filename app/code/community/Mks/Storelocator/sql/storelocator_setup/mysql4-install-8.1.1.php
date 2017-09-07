<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table {$this->getTable('mksstorelocator')} (id int not null auto_increment, name varchar(100), address varchar(255),zipcode varchar(100),city varchar(100),country_id varchar(100),phone varchar(100),fax varchar(100),description varchar(255),store_url varchar(100),email varchar(100),tradinghours varchar(100),radius varchar(100),image varchar(100),lat varchar(100),longt varchar(100),status varchar(100),primary key(id));


INSERT INTO {$this->getTable('mksstorelocator')} (`name`, `address`, `zipcode`, `city`, `country_id`, `phone`, `fax`, `description`, `store_url`, `image`, `tradinghours`, `radius`, `lat`, `longt`, `status`) VALUES 
('Malviya Nagar','Malviya Nagar Rajasthan (India)','302018','jaipur','india','8764226568','xxxxx','Malviya Nagar Rajasthan (India) ','http://www.magentocommerce.com/magento-connect/developer/mukeshsaini','storelocator/storelocator/map1.jpg','7:00 To 8:00PM', '200','26.857126400000000000','75.812719900000050000','0'),
('Karachi Sindh','Karachi Sindh Pakistan','302018','Karachi','Pakistan','8764226568','xxxxx','Karachi Sindh Pakistan ','http://www.magentocommerce.com/magento-connect/developer/mukeshsaini','storelocator/storelocator/map2.jpg','7:00 To 8:00PM', '200','24.893379000000000000','67.028060900000010000','0'),
('New York NY','New York NY United States','302018','New York','United States','8764226568','xxxxx','New York NY United States ','http://www.magentocommerce.com/magento-connect/developer/mukeshsaini','storelocator/storelocator/map3.jpg','7:00 To 8:00PM', '250','40.714352800000000000','-74.005973100000000000','0'),
('Malviya Nagar','Malviya Nagar Rajasthan (India)','302018','jaipur','india','8764226568','xxxxx','Malviya Nagar Rajasthan (India) ','http://www.magentocommerce.com/magento-connect/developer/mukeshsaini','storelocator/storelocator/map1.jpg','7:00 To 8:00PM', '200','26.857126400000000000','75.812719900000050000','0');

SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 
