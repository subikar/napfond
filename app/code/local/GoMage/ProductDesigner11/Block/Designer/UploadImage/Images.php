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
 * Designer uploaded images list block
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Block
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Block_Designer_UploadImage_Images extends Mage_Core_Block_Template
{
    /**
     * Return upload images
     *
     * @return GoMage_ProductDesigner_Model_Mysql4_UploadedImage_Collection
     */
    public function getUploadedImages()
    {
        $uploadedImage = Mage::getModel('gomage_designer/uploadedImage');
        return $uploadedImage->getCustomerUploadedImages();
    }

    /**
     * Return Image url
     *
     * @param string $image Image
     * @return string
     */
    public function getImageUrl($image)
    {

        $url = Mage::helper('gomage_designer/image_uploaded')->init($image)->resize(64, 64)->__toString();
        return $url;
    }

    public function getOriginImageUrl($image)
    {
        return rawurlencode(Mage::getSingleton('gomage_designer/uploadedImage_config')->getMediaUrl($image));
    }
}