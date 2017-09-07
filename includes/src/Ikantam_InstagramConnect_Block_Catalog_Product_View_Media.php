<?php
/**
 * iKantam
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade InstagramConnect to newer
 * versions in the future.
 *
 * @category    Ikantam
 * @package     Ikantam_InstagramConnect
 * @copyright   Copyright (c) 2012 iKantam LLC (http://www.ikantam.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Ikantam_InstagramConnect_Block_Catalog_Product_View_Media extends Mage_Catalog_Block_Product_View_Media
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
        	$this->_collection = Mage::helper('instagramconnect/product')->getInstagramGalleryImages($this->getProduct());
        }
        return $this->_collection;
    }

	/**
     * Retrieve gallery url
     *
     * @param null|Varien_Object $image
     * @return string
     */
    public function getInstagramGalleryUrl($image = null)
    {
    
        $params = array('id' => $this->getProduct()->getId());
        if ($image) {
            $params['image'] = $image->getImageId();
        }
        return $this->getUrl('instagramconnect/gallery/index', $params);
    }
}
