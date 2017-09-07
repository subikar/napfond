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

class Ikantam_InstagramConnect_Block_Homepage extends Mage_Core_Block_Template
{
	public function showInstagramImages()
	{
		$helper = Mage::helper('instagramconnect');
		return ($helper->isEnabled() && $helper->showImagesOnHomePage());
	}
	
	/**
     * Retrieve list of gallery images
     *
     * @return array|Varien_Data_Collection
     */
    public function getInstagramGalleryImages()
    {
        $images = new Varien_Data_Collection();
        
        $tags = Mage::helper('instagramconnect/image')->getTags();
        $users = Mage::helper('instagramconnect/image_user')->getUsers();

        $tags = array_merge($tags, $users);

        if (count($tags)) {
		    	$tagsCollection = Mage::getModel('instagramconnect/instagramimage')
		    		->getCollection()
		    		->setPageSize(Mage::helper('instagramconnect')->getHomePageLimit()) 
		    		->addFilter('is_approved', 1)
                    ->addFilter('is_visible', 1)
		    		->addFilter('tag', array('in' => $tags), 'public');

		    	foreach ($tagsCollection as $image) {
        			$images->addItem($image);
        		}
		    }
		    
        return $images;
    }
	
}
