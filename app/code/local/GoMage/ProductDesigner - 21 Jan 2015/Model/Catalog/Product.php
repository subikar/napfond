<?php
/**
 * GoMage Product Designer Extension
 *
 * @category     Extension
 * @copyright    Copyright (c) 2013 GoMage (http://www.gomage.com)
 * @author       GoMage
 * @license      http://www.gomage.com/license-agreement/  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use/
 * @version      Release: 1.0.0
 * @since        Available since Release 1.0.0
 */

/**
 * Catalog product model
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Model
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Model_Catalog_Product extends Mage_Catalog_Model_Product
{
    const GALLERY_ATTRIBUTE_CODE = 'media_gallery';

    /**
     * Retrive media gallery images
     *
     * @return Varien_Data_Collection
     */
    public function getMediaGalleryImages()
    {
        if (!Mage::helper('gomage_designer')->isEnabled()) {
            return parent::getMediaGalleryImages();
        }
        if(!$this->hasData('media_gallery_images') && is_array($this->getMediaGallery('images'))) {
            $images = new Varien_Data_Collection();
            $designId = (int) Mage::app()->getRequest()->getParam('design_id', false);
            if ($designId) {
                $design = Mage::getModel('gomage_designer/design')->load($designId);
                $designColor = $design->getColor();
                $designImagesCollection = $this->getDesignProductImages($designId);
                $designImages = array();
                foreach ($designImagesCollection as $_designImage) {
                    $designImages[$_designImage->getImageId()] = $_designImage;
                }
            }
            foreach ($this->getMediaGallery('images') as $image) {
                $imageDisabled = $image['disabled'];
                if ($designId && $designColor) {
                    if ($designColor == $image['color']) {
                        $imageDisabled = false;
                    } else {
                        $imageDisabled = true;
                    }
                } elseif (isset($designImages[$image['value_id']])) {
                    $imageDisabled = false;
                }

                if ($imageDisabled) {
                    continue;
                }
                if (!empty($designImages) && isset($designImages[$image['value_id']])) {
                    $designImage = $designImages[$image['value_id']];
                    $mediaConfig = $this->getDesignMediaConfig();
                    $imageExtension = strtolower(pathinfo($designImage['image'], PATHINFO_EXTENSION));
                    if ($imageExtension == 'pdf') {
                        $designImage['image'] = str_replace('.pdf', '.jpg', $designImage['image']);
                    }
                    $image['original_file'] = $image['file'];
                    $image['file'] = $designImage['image'];
                    $image['design_id'] = $designId;
                } else {
                    $mediaConfig = $this->getMediaConfig();
                }
                $image['url'] = $mediaConfig->getMediaUrl($image['file']);
                $image['path'] = $mediaConfig->getMediaPath($image['file']);
                $image['id'] = isset($image['value_id']) ? $image['value_id'] : null;
                $images->addItem(new Varien_Object($image));
            }
            $this->setData('media_gallery_images', $images);
        }

        return $this->getData('media_gallery_images');
    }

    public function getImage()
    {
        $designId = (int) Mage::app()->getRequest()->getParam('design_id', false);
        if ($designId) {
            $images = $this->getMediaGalleryImages();
            $baseImage = null;
            foreach ($images as $image) {
                if (is_null($baseImage) || $image->getOriginalFile() == $this->getData('image')) {
                    $baseImage = $image->getFile();
                }
            }
            if ($baseImage) {
                $this->setData('image', $baseImage);
            }
        }

        return $this->getData('image');
    }

    public function getDesignMediaConfig()
    {
        return Mage::getSingleton('gomage_designer/design_config');
    }

    /**
     * Retrieve Product URL with design
     *
     * @param string $designId Design Id
     * @param bool   $useSid   Use SID
     * @return string
     */
    public function getDesignedProductUrl($designId, $useSid = null)
    {
        if ($useSid === null) {
            $useSid = Mage::app()->getUseSessionInUrl();
        }

        $params = array();
        if (!$useSid) {
            $params['_nosid'] = true;
        }
        if ($designId) {
            $params['_query'] = array('design_id' => $designId);
        }
        return $this->getUrlModel()->getUrl($this, $params);
    }

    /**
     * Return design product images
     *
     * @param int $designId Design Id
     * @return mixed
     */
    public function getDesignProductImages($designId)
    {
        $collection = Mage::getModel('gomage_designer/design_image')->getCollection()
            ->addFieldToFilter('design_id', $designId)
            ->addFieldToFilter('product_id', $this->getId());

        return $collection;
    }

    /**
     * Return product colors
     *
     * @return bool
     */
    public function getProductColors()
    {
        if ($this->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE
            && Mage::helper('gomage_designer')->hasColorAttribute()) {
            $colors = Mage::getResourceModel('gomage_designer/catalog_product_type_configurable')->getProductColors($this->getId());
            foreach ($colors as &$color) {
                if (isset($color['image']) && $color['image'] != null) {
                    $color['image'] = Mage::getBaseUrl('media') . 'option_image'. DS . $color['image'];
                }
            }

            return $colors;
        }

        return false;
    }

    /**
     * Retrieve media gallery
     *
     * @return GoMage_ProductDesigner_Model_Catalog_Product_Attribute_Backend_Media
     */
    public function getMediaGalleryAttribute()
    {
        $attributes = $this->getTypeInstance(true)->getSetAttributes($this);
        if (!isset($attributes[self::GALLERY_ATTRIBUTE_CODE])) {
            return false;
        }
        $galleryAttribute = $attributes[self::GALLERY_ATTRIBUTE_CODE];

        return $galleryAttribute->getBackend();
    }

    public function hasImagesForDesign()
    {
        $config = Mage::helper('gomage_designer')->getProductSettingForEditor($this);
        if (empty($config['images'])) {
            return false;
        }

        return true;
    }
}