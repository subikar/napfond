<?php

$installer = $this;

$installer->startSetup();
/**
 * General fileds
 */
$installer->getConnection()->addColumn($installer->getTable('eadesign/pdfgenerator'), 'pdft_is_active', array(
    'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
    'unsigned' => true,
    'nullable' => false,
    'default' => '0',
    'comment' => 'Active setting'
));

$installer->getConnection()->addColumn($installer->getTable('eadesign/pdfgenerator'), 'pdft_default', array(
    'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
    'unsigned' => true,
    'nullable' => false,
    'default' => '0',
    'comment' => 'Default setting'
));
/**
 * Header fields
 */
$installer->getConnection()->addColumn($installer->getTable('eadesign/pdfgenerator'), 'pdfth_header', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'unsigned' => true,
    'nullable' => false,
    'default' => '',
    'comment' => 'Header body'
));


/**
 * Footer fields
 */
$installer->getConnection()->addColumn($installer->getTable('eadesign/pdfgenerator'), 'pdftf_footer', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'unsigned' => true,
    'nullable' => false,
    'default' => '',
    'comment' => 'Footer body'
));


/**
 * Css fileds
 */
$installer->getConnection()->addColumn($installer->getTable('eadesign/pdfgenerator'), 'pdft_css', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'unsigned' => true,
    'nullable' => false,
    'default' => '',
    'comment' => 'Css body'
));

/**
 * Settings fields
 */
$installer->getConnection()->addColumn($installer->getTable('eadesign/pdfgenerator'), 'pdftc_customchek', array(
    'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
    'unsigned' => true,
    'nullable' => false,
    'default' => '0',
    'comment' => 'Paper custom check'
));

$installer->getConnection()->addColumn($installer->getTable('eadesign/pdfgenerator'), 'pdft_customwidth', array(
    'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'scale' => 4,
    'precision' => 12,
    'nullable' => false,
    'default' => '0.0000',
    'comment' => 'Paper custom Width'
));

$installer->getConnection()->addColumn($installer->getTable('eadesign/pdfgenerator'), 'pdft_customheight', array(
    'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'scale' => 4,
    'precision' => 12,
    'nullable' => false,
    'default' => '0.0000',
    'comment' => 'Paper custom Height'
));


$installer->getConnection()->addColumn($installer->getTable('eadesign/pdfgenerator'), 'pdftm_top', array(
    'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'scale' => 4,
    'precision' => 12,
    'nullable' => false,
    'default' => '0.0000',
    'comment' => 'Paper margins top'
));

$installer->getConnection()->addColumn($installer->getTable('eadesign/pdfgenerator'), 'pdftm_bottom', array(
    'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'scale' => 4,
    'precision' => 12,
    'nullable' => false,
    'default' => '0.0000',
    'comment' => 'Paper margins bottom'
));

$installer->getConnection()->addColumn($installer->getTable('eadesign/pdfgenerator'), 'pdftm_left', array(
    'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'scale' => 4,
    'precision' => 12,
    'nullable' => false,
    'default' => '0.0000',
    'comment' => 'Paper margins left'
));

$installer->getConnection()->addColumn($installer->getTable('eadesign/pdfgenerator'), 'pdftm_right', array(
    'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'scale' => 4,
    'precision' => 12,
    'nullable' => false,
    'default' => '0.0000',
    'comment' => 'Paper margins right'
));


$installer->run("
    
insert into {$this->getTable('eadesign/pdfgenerator')} 
    
(`pdftemplate_id`, `pdftemplate_name`, `pdftemplate_desc`, `pdftemplate_body`, `pdft_type`, `pdft_filename`, `pdftp_format`, `pdft_orientation`, `created_time`, `update_time`, `template_store_id`, `pdft_is_active`, `pdft_default`, `pdfth_header`, `pdftf_footer`, `pdft_css`, `pdftc_customchek`, `pdft_customwidth`, `pdft_customheight`, `pdftm_top`, `pdftm_bottom`, `pdftm_left`, `pdftm_right`) VALUES

(1, 'Invoice Template', 'The template for all stores.', '<!DOCTYPE html>\r\n<html>\r\n<head lang=\"en\">\r\n    <meta charset=\"UTF-8\">\r\n    <link rel=\"stylesheet\" href=\"style.css\" media=\"all\"/>\r\n    <title></title>\r\n</head>\r\n<div class=\"body\" style=\"position: relative;    width: 21cm;    height: 29.7cm;    margin: 0 auto;    color: #555555;    background: #FFFFFF;font-family: Arial, sans-serif;\r\n    font-size: 14px;\r\n    font-family: Arial;\">\r\n<div class=\"clearfix header\" style=\" padding: 10px 0;    margin-bottom: 20px;    border-bottom: 1px solid #AAAAAA;\">\r\n    <div id=\"logo\" style=\"float: left;    margin-top: 8px;    width:49%;\">\r\n        <img src=\"https://www.eadesign.ro/logo.png\" style=\"  height: 70px;\" alt=\"\"/>\r\n    </div>\r\n    <div id=\"company\" style=\"  float: right;    text-align: right;    width:49%;\">\r\n        <h2 class=\"name\" style=\"font-size: 1.4em;    font-weight: normal;    margin: 0;\">EaDesign Web Development</h2>\r\n\r\n        <div>Lascar Catargi nr.10, et.1,Iasi,Romania</div>\r\n        <div>0232 272221</div>\r\n        <div><a href=\"mailto:office@eadesign.ro\">office@eadesign.ro</a></div>\r\n    </div>\r\n</div>\r\n<div class=\"main\">\r\n    <div id=\"details\" class=\"clearfix\" style=\"margin-bottom: 50px;\">\r\n        <div id=\"client\" style=\" padding-left: 6px;    border-left: 6px solid #000;    float: left;    width:49%;\">\r\n            <div class=\"to\" style=\"color: #777777;\">INVOICE TO:</div>\r\n            <div class=\"name\">{{var billing_address}}</div>\r\n        </div>\r\n        <div id=\"invoice\" style=\"float: right;    text-align: right;    width:49%;\">\r\n            <h1 style=\"color: #000;    font-size: 2.4em;    line-height: 1em;    font-weight: normal;    margin: 0  0 10px 0;\">INVOICE {{var ea_invoice_id}}</h1>\r\n            <div class=\"date\" style=\"font-size: 1.1em;    color: #777777;\">Date of Invoice: {{var ea_invoice_date}}</div>\r\n            <div class=\"date\" style=\"font-size: 1.1em;    color: #777777;\">Status: {{var ea_invoice_status}}</div>\r\n        </div>\r\n    </div>\r\n    <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"width: 100%;    border-collapse: collapse;    border-spacing: 0;    margin-bottom: 20px;\">\r\n        <thead>\r\n        <tr>\r\n            <th class=\"no\">#</th>\r\n            <th class=\"desc\">DESCRIPTION</th>\r\n            <th class=\"unit\">PRICE</th>\r\n            <th class=\"qty\">QTY</th>\r\n            <th class=\"total\">TOTAL</th>\r\n        </tr>\r\n        </thead>\r\n        <tbody>\r\n        <tr>\r\n            <td colspan=\"5\">##productlist_start##</td>\r\n        </tr>\r\n        <tr>\r\n            <td class=\"no\">\r\n                <p>{{var items_position}}</p>\r\n            </td>\r\n            <td class=\"desc\">\r\n                <p>{{var items_name}}</p>\r\n\r\n                <p>{{var bundle_items_option}}</p>\r\n\r\n                <p>{{var items_sku}}</p>\r\n\r\n                <p>{{var product_options}}</p>\r\n            </td>\r\n            <td class=\"unit\">\r\n                <p>{{var itemcarptice}}</p>\r\n            </td>\r\n            <td class=\"qty\">\r\n                <p>{{var items_qty}}</p>\r\n            </td>\r\n            <td class=\"total\">{{var itemsubtotal}}</td>\r\n        </tr>\r\n        <tr>\r\n            <td colspan=\"5\">##productlist_end##</td>\r\n        </tr>\r\n        </tbody>\r\n        <tfoot>\r\n        <tr>\r\n            <td colspan=\"2\">&nbsp;</td>\r\n            <td colspan=\"2\">SUBTOTAL</td>\r\n            <td>{{var subtotalincludingtax}}</td>\r\n        </tr>\r\n        <tr>\r\n            <td colspan=\"2\">&nbsp;</td>\r\n            <td colspan=\"2\">SHIPPING TAX</td>\r\n            <td>{{var shipping_tax}}</td>\r\n        </tr>\r\n        <tr>\r\n            <td colspan=\"2\" style=\"border-top: 1px solid #ff8b00;\">&nbsp;</td>\r\n            <td class=\"grandtotal\" colspan=\"2\" style=\" color: #ff8b00;    font-size: 1.4em;    border-top: 1px solid #ff8b00;\">GRAND TOTAL</td>\r\n            <td class=\"grandtotal\" style=\" color: #ff8b00;    font-size: 1.4em;    border-top: 1px solid #ff8b00;\">{{var grandtotalincludingtax}}</td>\r\n        </tr>\r\n        </tfoot>\r\n    </table>\r\n    <div id=\"thanks\" style=\"font-size: 2em;    margin-bottom: 50px;\">Thank you!</div>\r\n</div>\r\n</div>\r\n</html>', 'invoicepdftemplate', 'invoice {{var ea_invoice_id}} {{var ea_invoice_date}} {{var ea_invoice_status}} ', '0', 'portrait', '2013-05-13 08:22:18', '2015-10-22 13:12:00', 0, 1, 0, '', '<div style=\"text-align: center; color: #777777; border-top: 1px solid #AAAAAA;\">Invoice was created on a computer and is valid without the signature and seal.</div>\r\n<div style=\"text-align: center;\">Page number {PAGENO}/{nbpg}. Call us at 0800 454 454 at eny time!</div>', '@font-face {\r\n  font-family: Arial;\r\n}\r\n\r\n.clearfix:after {\r\n  content: \"\";\r\n  display: table;\r\n  clear: both;\r\n}\r\n\r\na {\r\n  color: #000;\r\n  text-decoration: none;\r\n}\r\ntable {\r\n  width: 100%;\r\n  border-collapse: collapse;\r\n  border-spacing: 0;\r\n  margin-bottom: 20px;\r\n}\r\n\r\ntable th,\r\ntable td {\r\n  \r\n  background: #EEEEEE;\r\n  text-align: center;\r\n  border-bottom: 1px solid #FFFFFF;\r\n}\r\n\r\ntable th {\r\n  white-space: nowrap;\r\n  font-weight: normal;\r\n}\r\n\r\ntable td {\r\n  text-align: right;\r\n}\r\n\r\ntable td h3{\r\n  color: #ff8b00;\r\n  font-size: 1.2em;\r\n  font-weight: normal;\r\n  margin: 0 0 0.2em 0;\r\n}\r\n\r\ntable .no {\r\n  color: #FFFFFF;\r\n  font-size: 1.6em;\r\n  background: #ff8b00;\r\n}\r\ntable .desc {\r\n  text-align: left;\r\n}\r\n\r\ntable .unit {\r\n  background: #DDDDDD;\r\n}\r\n\r\ntable .qty {\r\n}\r\n\r\ntable .total {\r\n  background: #ff8b00;\r\n  color: #FFFFFF;\r\n}\r\n\r\ntable td.unit,\r\ntable td.qty,\r\ntable td.total {\r\n  font-size: 1.2em;\r\n}\r\n\r\ntable tbody tr:last-child td {\r\n  border: none;\r\n}\r\n\r\ntable tfoot td {\r\n  padding: 10px 20px;\r\n  background: #FFFFFF;\r\n  border-bottom: none;\r\n  font-size: 1.2em;\r\n  white-space: nowrap;\r\n  border-top: 1px solid #AAAAAA;\r\n}\r\n\r\ntable tfoot tr:first-child td {\r\n  border-top: none;\r\n}\r\n\r\n.grandtotal {\r\n  color: #ff8b00;\r\n  font-size: 1.4em;\r\n  border-top: 1px solid #ff8b00;\r\n\r\n}\r\n\r\ntable tfoot tr td:first-child {\r\n  border: none;\r\n}\r\n\r\n#thanks{\r\n  font-size: 2em;\r\n  margin-bottom: 50px;\r\n}\r\n\r\n#notices{\r\n  padding-left: 6px;\r\n  border-left: 6px solid #000;\r\n}\r\n\r\n#notices .notice {\r\n  font-size: 1.2em;\r\n}\r\n', 0, '1.0000', '1.0000', '7.0000', '7.0000', '20.0000', '20.0000'),
(2, 'Invoice Landscape', '', '<div class=\"body\" style=\"position: relative; width: 21cm; height: 29.7cm; margin: 0 auto; color: #555555; background: #FFFFFF; font-family: Arial; font-size: 14px;\">\r\n<div class=\"clearfix header\" style=\"padding: 10px 0; margin-bottom: 20px; border-bottom: 1px solid #AAAAAA;\">\r\n<div id=\"logo\" style=\"float: left; margin-top: 8px; width: 49%;\"><img style=\"height: 70px;\" src=\"https://www.eadesign.ro/logo.png\" alt=\"\" /></div>\r\n<div id=\"company\" style=\"float: right; text-align: right; width: 49%;\">\r\n<h2 class=\"name\" style=\"font-size: 1.4em; font-weight: normal; margin: 0;\">EaDesign Web Development</h2>\r\n<div>Lascar Catargi nr.10, et.1,Iasi,Romania</div>\r\n<div>0232 272221</div>\r\n<div><a href=\"mailto:office@eadesign.ro\">office@eadesign.ro</a></div>\r\n</div>\r\n</div>\r\n<div class=\"main\">\r\n<div id=\"details\" class=\"clearfix\" style=\"margin-bottom: 50px;\">\r\n<div id=\"client\" style=\"padding-left: 6px; border-left: 6px solid #000; float: left; width: 49%;\">\r\n<div class=\"to\" style=\"color: #777777;\">INVOICE TO:</div>\r\n<div class=\"name\" style=\"font-size: 0.5em;\">{{var billing_address}}</div>\r\n</div>\r\n<div id=\"invoice\" style=\"float: right; text-align: right; width: 49%;\">\r\n<h1 style=\"color: #000; font-size: 1.6em; line-height: 1em; font-weight: normal; margin: 0  0 10px 0;\">INVOICE {{var ea_invoice_id}}</h1>\r\n<div class=\"date\" style=\"font-size: 1.1em; color: #777777;\">Date of Invoice: {{var ea_invoice_date}}</div>\r\n<div class=\"date\" style=\"font-size: 1.1em; color: #777777;\">Status: {{var ea_invoice_status}}</div>\r\n</div>\r\n</div>\r\n<table style=\"width: 100%; border-collapse: collapse; border-spacing: 0; margin-bottom: 20px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<thead>\r\n<tr><th class=\"no\">#</th><th class=\"desc\">NAME</th><th class=\"unit\">SKU</th><th class=\"qty\">IMAGE</th><th class=\"unit\">PRICE</th><th class=\"qty\">DISCOUNT</th><th class=\"unit\">MANUFACTURER</th><th class=\"qty\">QTY</th><th class=\"total\">TOTAL</th></tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td colspan=\"9\">##productlist_start##</td>\r\n</tr>\r\n<tr>\r\n<td class=\"no\">\r\n<p>{{var items_position}}</p>\r\n</td>\r\n<td class=\"desc\">\r\n<p>{{var items_name}}</p>\r\n</td>\r\n<td class=\"unit\">\r\n<p>{{var items_sku}}</p>\r\n<p>{{var product_options}}</p>\r\n</td>\r\n<td class=\"qty\">{{var productimage}}</td>\r\n<td class=\"unit\">\r\n<p>{{var itemcarptice}}</p>\r\n</td>\r\n<td class=\"qty\">{{var items_discount}}</td>\r\n<td class=\"unit\">{{var manufacturer}}</td>\r\n<td class=\"qty\">\r\n<p>{{var items_qty}}</p>\r\n</td>\r\n<td class=\"total\">{{var itemsubtotal}}</td>\r\n</tr>\r\n<tr>\r\n<td colspan=\"9\">##productlist_end##</td>\r\n</tr>\r\n</tbody>\r\n<tfoot>\r\n<tr>\r\n<td colspan=\"6\">&nbsp;</td>\r\n<td colspan=\"2\">SUBTOTAL</td>\r\n<td>{{var subtotalincludingtax}}</td>\r\n</tr>\r\n<tr>\r\n<td colspan=\"6\">&nbsp;</td>\r\n<td colspan=\"2\">SHIPPING TAX</td>\r\n<td>{{var shipping_amountincltax}}</td>\r\n</tr>\r\n<tr>\r\n<td colspan=\"6\">&nbsp;</td>\r\n<td colspan=\"2\">DISCOUNT</td>\r\n<td>{{var discountammount}}</td>\r\n</tr>\r\n<tr>\r\n<td colspan=\"6\">&nbsp;</td>\r\n<td colspan=\"2\">Total Tax</td>\r\n<td>{{var all_tax_ammount}}</td>\r\n</tr>\r\n<tr>\r\n<td colspan=\"6\">&nbsp;</td>\r\n<td class=\"grandtotal\" colspan=\"2\">GRAND TOTAL</td>\r\n<td class=\"grandtotal\">{{var grandtotalincludingtax}}</td>\r\n</tr>\r\n</tfoot>\r\n</table>\r\n<div id=\"thanks\">Thank you!</div>\r\n</div>\r\n</div>', 'invoicepdftemplate', 'invoice {{var ea_invoice_id}} {{var ea_invoice_date}} {{var ea_invoice_status}} ', '0', 'landscape', '2015-10-22 06:02:39', '2015-10-22 12:56:00', 0, 0, 0, '', '<div style=\"text-align: center;\">Page number {PAGENO}/{nbpg}. Call us at 0800 454 454 at eny time!</div>', '@font-face {\r\n  font-family: Arial;\r\n}\r\n\r\n.clearfix:after {\r\n  content: \"\";\r\n  display: table;\r\n  clear: both;\r\n}\r\n\r\na {\r\n  color: #000;\r\n  text-decoration: none;\r\n}\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\ntable {\r\n  width: 100%;\r\n  border-collapse: collapse;\r\n  border-spacing: 0;\r\n  margin-bottom: 20px;\r\n}\r\n\r\ntable th,\r\ntable td {\r\n  padding: 20px;\r\n  background: #EEEEEE;\r\n  text-align: center;\r\n  border-bottom: 1px solid #FFFFFF;\r\n}\r\n\r\ntable th {\r\n  white-space: nowrap;        \r\n  font-weight: normal;\r\n}\r\n\r\ntable td {\r\n  text-align: right;\r\n}\r\n\r\ntable td h3{\r\n  color: #ff8b00;\r\n  font-size: 1.2em;\r\n  font-weight: normal;\r\n  margin: 0 0 0.2em 0;\r\n}\r\n\r\ntable .no {\r\n  color: #FFFFFF;\r\n  font-size: 1.6em;\r\n  background: #ff8b00;\r\n}\r\n\r\ntable .desc {\r\n  text-align: left;\r\n}\r\n\r\ntable .unit {\r\n  background: #DDDDDD;\r\n}\r\n\r\ntable .qty {\r\n}\r\n\r\ntable .total {\r\n  background: #ff8b00;\r\n  color: #FFFFFF;\r\n}\r\n\r\ntable td.unit,\r\ntable td.qty,\r\ntable td.total {\r\n  font-size: 1.2em;\r\n}\r\n\r\ntable tbody tr:last-child td {\r\n  border: none;\r\n}\r\n\r\ntable tfoot td {\r\n  padding: 10px 20px;\r\n  background: #FFFFFF;\r\n  border-bottom: none;\r\n  font-size: 1.2em;\r\n  white-space: nowrap; \r\n  border-top: 1px solid #AAAAAA; \r\n}\r\n\r\ntable tfoot tr:first-child td {\r\n  border-top: none; \r\n}\r\n\r\n.grandtotal {\r\n  color: #ff8b00;\r\n  font-size: 1.4em;\r\n  border-top: 1px solid #ff8b00;\r\n\r\n}\r\n\r\ntable tfoot tr td:first-child {\r\n  border: none;\r\n}\r\n\r\n#thanks{\r\n  font-size: 2em;\r\n  margin-bottom: 50px;\r\n}\r\n\r\n#notices{\r\n  padding-left: 6px;\r\n  border-left: 6px solid #000;\r\n}\r\n\r\n#notices .notice {\r\n  font-size: 1.2em;\r\n}\r\n\r\n', 0, '1.0000', '1.0000', '10.0000', '10.0000', '20.0000', '20.0000');
    
");

$installer->endSetup();



