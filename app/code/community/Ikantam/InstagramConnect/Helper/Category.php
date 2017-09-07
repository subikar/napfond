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
class Ikantam_InstagramConnect_Helper_Category extends Mage_Core_Helper_Abstract
{
    /**
     * Retrive media gallery images
     *
     * @return Varien_Data_Collection
     */
    public function getInstagramGalleryImages($category)
    {
        if(!$category->hasData('instagram_gallery_images')) {
            $images = new Varien_Data_Collection();

            $tags  = $this->_getCategoryTags($category);

            if (count($tags)) {
                $tagsCollection = Mage::getModel('instagramconnect/instagramimage')
                    ->getCollection()
                    ->setPageSize(Mage::helper('instagramconnect')->getProductPageLimit())
                    ->addFilter('is_approved', 1)
                    ->addFilter('is_visible', 1)
                    ->addFilter('tag', array('in' => $tags), 'public');

                foreach ($tagsCollection as $image) {
                    $images->addItem($image);
                }
            }

            $category->setData('instagram_gallery_images', $images);
        }

        return $category->getData('instagram_gallery_images');
    }

    protected function _getCategoryTags($category)
    {
        $tags  = array();

        // Tags
        $sources = explode(',', $category->getInstagramCategorySource());
        foreach ($sources as $source) {
            $source = base64_decode($source);
            if (!$source) {
                continue;
            }
            $tags[]  = '#' . ltrim($source, '#');
        }

        // Users
        $sources = explode(',', $category->getInstagramCategorySourceUser());
        foreach ($sources as $source) {
            $source = base64_decode($source);
            if (!$source) {
                continue;
            }
            $tags[]  = '@' . ltrim($source, '@');
        }

        return $tags;

    }
}
