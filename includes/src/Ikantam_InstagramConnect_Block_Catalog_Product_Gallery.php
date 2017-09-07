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
class Ikantam_InstagramConnect_Block_Catalog_Product_Gallery extends Mage_Catalog_Block_Product_Gallery
{
    public function getInstagramGalleryCollection()
    {
    	return Mage::helper('instagramconnect/product')->/*$this->getProduct()->*/getInstagramGalleryImages($this->getProduct());
    }
    
    public function getCurrentInstagramImage()
    {
        $imageId = $this->getRequest()->getParam('image');
        $image = null;
        
        if ($imageId) {
            $image = Mage::getModel('instagramconnect/instagramimage')->load($imageId);
        }
        
        return $image;
    }
    
    public function getInstagramImageUrl()
    {
        return $this->getCurrentInstagramImage()->getStandardResolutionUrl();
    }
    
    public function getPreviousInstagramImage()
    {
        $current = $this->getCurrentInstagramImage();
        if (!$current) {
            return false;
        }
        $previous = false;
        foreach ($this->getInstagramGalleryCollection() as $image) {
            if ($image->getImageId() == $current->getImageId()) {
                return $previous;
            }
            $previous = $image;
        }
        return $previous;
    }
    
    public function getNextInstagramImage()
    {
        $current = $this->getCurrentInstagramImage();
        if (!$current) {
            return false;
        }

        $next = false;
        $currentFind = false;
        foreach ($this->getInstagramGalleryCollection() as $image) {
            if ($currentFind) {
                return $image;
            }
            if ($image->getImageId() == $current->getImageId()) {
                $currentFind = true;
            }
        }
        return $next;
    }

    public function getPreviousInstagramImageUrl()
    {
        if ($image = $this->getPreviousInstagramImage()) {
            return $this->getUrl('*/*/*', array('_current'=>true, 'image'=>$image->getImageId()));
        }
        return false;
    }

    public function getNextInstagramImageUrl()
    {
        if ($image = $this->getNextInstagramImage()) {
            return $this->getUrl('*/*/*', array('_current'=>true, 'image'=>$image->getImageId()));
        }
        return false;
    }
}
