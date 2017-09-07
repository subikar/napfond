<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @copyright   Copyright (c) 2014 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


class Mage_Checkout_Block_Success extends Mage_Core_Block_Template
{
    public function getRealOrderId()
    {
        $order = Mage::getModel('sales/order')->load($this->getLastOrderId());
        #print_r($order->getData());
        return $order->getIncrementId();
    }

	public function get_val($val)
	{
		$read = Mage::helper('function/more')->getDbReadObject();
		$qry = $read->query("SELECT `value` FROM `eav_attribute_option_value` WHERE `option_id` = '".$val."'");
		$row = $qry->fetch();
		return $row['value'];
	}

	public function update_stock($sxml, $code, $color_id, $color_name, $size_id, $size_name, $cat_id)
	{
		$write = Mage::getSingleton( 'core/resource' )->getConnection('core_write');
		foreach ($sxml as $data) 
		{
			foreach ($data->style_id as $datastyle)
			{
				if($datastyle == $code)
				{
					foreach ($data->style_color as $datacolor) 
					{
						if($datacolor->style_color_name == $color_name)
						{
							foreach ($datacolor->style_size as $datasize) 
							{
								if($datasize->size_name == $size_name)
								{
									$main_stock = $datasize->size_stock;
									$prod_collection = Mage::getModel('catalog/category')->load($cat_id);
									$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*');
									foreach($collection as $product) 
									{
										if($product->isConfigurable())
										{
											$prod_id = $product->getId();
											$conf = Mage::getModel('catalog/product_type_configurable')->setProduct($product);
											$simple_collection = $conf->getUsedProductCollection()->addAttributeToSelect('*');
											$simple_collection->addAttributeToFilter('color', $color_id)->addAttributeToFilter('Size', $size_id);
											foreach($simple_collection as $simple_product)
											{
												$id = $simple_product->getId();
												$write->query("Update `cataloginventory_stock_item` SET `qty` = '".$main_stock."' WHERE `product_id` = '".$id."'");
												$write->query("Update `cataloginventory_stock_status` SET `qty` = '".$main_stock."' WHERE `product_id` = '".$id."'");
												$write->query("Update `cataloginventory_stock_status_idx` SET `qty` = '".$main_stock."' WHERE `product_id` = '".$id."'");
											}
										}			
									}
								}
							}
						}
					}
				}
			}
		}
	}
}
