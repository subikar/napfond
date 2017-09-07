<?php

class Darsh_Stockqtny_Model_Observer extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        //$this->_init('stockqty/stockqty');
    }

    public function hookToControllerActionPreDispatchDarsh($observer) {/*

        $configValue = (int) Mage::getStoreConfig('cataloginventory/item_options/min_qty_category');

        if ($observer->getEvent()->getControllerAction()->getFullActionName() == 'adminhtml_system_config_save') {
            if ($observer->getEvent()->getControllerAction()->getRequest()->getPost('groups')) {
                $groups = $observer->getEvent()->getControllerAction()->getRequest()->getPost('groups');
                $min_qty_category = (int) $groups['item_options']['fields']['min_qty_category']['value'];

                if ($min_qty_category) {
                     if ($min_qty_category != $configValue) {



                    $children = Mage::getModel('catalog/category')->getCategories(10);
                    $categoryarray = array();
                    $counter = 0;
                    $categoryarray[$counter] = 10;
                    foreach ($children as $category) {
                        $counter++;
                        $categoryarray[$counter] = $category->getEntityId();
                    }
                    foreach ($categoryarray as $cat_id) {
                        $products = Mage::getModel('catalog/category')->load($cat_id)
                                ->getProductCollection();
                                                
                        $resource = Mage::getSingleton('core/resource');
                        $table = $resource->getTableName('cataloginventory/stock_item');
                        foreach ($products as $productdata) {
                           // if($productdata->getTypeId() != 'simple'){
                               // echo "<pre/>";
                               // print_r($productdata->getData());exit;
                           // }
                            //print_r($productdata->getTypeId());exit;
                            $write = Mage::getSingleton('core/resource')->getConnection('core_write');
                            $query1 = 'SELECT * FROM ' . $table . ' WHERE product_id = ' . $productdata->getEntityId() . '';
                            $readresult = $write->query($query1);
                            $row = $readresult->fetchAll();
                            foreach ($row as $data) {
                                $write1 = Mage::getSingleton('core/resource')->getConnection('core_write');
                                if ($data['qty'] <= $min_qty_category) {
                                    $query2 = 'update ' . $table . ' SET min_qty=' . $min_qty_category . ',use_config_min_qty=0,is_in_stock=0 WHERE product_id = ' . $productdata->getEntityId() . '';
                                    $write1->query($query2);
                                } else {
                                    $query3 = 'update ' . $table . ' SET min_qty=' . $min_qty_category . ',use_config_min_qty=0,is_in_stock=1 WHERE product_id = ' . $productdata->getEntityId() . '';
                                    $write1->query($query3);
                                }
                            }
                        }
                    }
                }
            }
            }
        }*/
    }

}
