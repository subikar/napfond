<?php
/**
 * iKantam LLC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the iKantam EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://magento.ikantam.com/store/license-agreement
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade
 * HouseConfigurator Module to newer versions in the future.
 *
 * @category   Ikantam
 * @package    Ikantam_HouseConfigurator
 * @author     iKantam Team <support@ikantam.com>
 * @copyright  Copyright (c) 2012 iKantam LLC (http://www.ikantam.com)
 * @license    http://magento.ikantam.com/store/license-agreement  iKantam EULA
 */
class Ikantam_InstagramConnect_Block_Catalog_Category_View_Images extends Mage_Catalog_Block_Category_View
{

    protected $_collection = array();

    public function showInstagramImages()
    {
        $helper = Mage::helper('instagramconnect');
        return ($helper->isEnabled() && $helper->showImagesOnProductPage() && count($this->getInstagramGalleryImages()) > 0);
    }

    /**
     * Retrieve list of gallery images
     *
     * @return array|Varien_Data_Collection
     */
    public function getInstagramGalleryImages()
    {
        if (!$this->_isGalleryDisabled && !$this->_collection) {
            $this->_collection = Mage::helper('instagramconnect/category')->getInstagramGalleryImages($this->getCurrentCategory());
        }
        return $this->_collection;
    }

}
