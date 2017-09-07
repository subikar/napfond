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
 * Admin designs view block
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Block
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Block_Adminhtml_Design_View extends Mage_Adminhtml_Block_Widget_Container
{
    protected $_imageCollection;

    protected $_columnCount = 3;

    protected $_design;

    public function __construct()
    {
        if (extension_loaded('zip') && ($url = $this->getDownloadAllUrl())) {
            $this->_addButton('download', array(
                'label'     => Mage::helper('gomage_designer')->__('Download All'),
                'onclick'   => 'setLocation(\'' . $url .'\')'
            ));
        }
    }

    /**
     * Return column count
     *
     * @return int|mixed
     */
    public function getColumnCount()
    {
        if ($this->hasData('column_count')) {
            return $this->getData('column_count');
        }

        return $this->getData($this->_columnCount);
    }
    /**
     * Return images collection
     *
     * @return GoMage_ProductDesigner_Model_Mysql4_Design_Image_Collection
     */
    public function getImagesCollection()
    {
        return $this->_initImagesCollection();
    }

    /**
     * Initialize images collection
     *
     * @return GoMage_ProductDesigner_Model_Mysql4_Design_Collection
     */
    protected function _initImagesCollection()
    {
        if (is_null($this->_imageCollection)) {
            $designId = $this->getDesignId();
            $collection = Mage::getModel('gomage_designer/design_image')->getCollection()
                ->getImageCollectionByDesign($designId);
            $this->_imageCollection = $collection;
        }

        return $this->_imageCollection;
    }

    /**
     * Return design id
     *
     * @return mixed
     */
    public function getDesignId()
    {
        return $this->getRequest()->getParam('design_id', false);
    }

    /**
     * Return image
     *
     * @param GoMage_ProductDesigner_Model_Design_Image $image Image
     * @param array                                     $size  Image Size
     * @return Varien_Image
     */
    public function getImage($image, $size)
    {
        return Mage::helper('gomage_designer/image_design')->init($image)->resize($size);
    }

    /**
     * Return download url
     *
     * @param int $imageId Image Id
     * @return string
     */
    public function getDownloadUrl($imageId, $type)
    {
        return Mage::helper('adminhtml')->getUrl('*/designer_design/download', array(
            'image_id' => $imageId,
            'type' => $type
        ));
    }

    public function getDesign()
    {
        if (is_null($this->_design)) {
            if ($designId = $this->getDesignId()) {
                $design = Mage::getModel('gomage_designer/design')->load($designId);
                if ($design && $design->getId()) {
                    $this->_design = $design;
                }
            }
        }

        return $this->_design;
    }

    public function getDownloadAllUrl()
    {
        if ($design = $this->getDesign()) {
            return Mage::helper('adminhtml')->getUrl('*/designer_design/downloadAll',
                array('design_id' => $design->getId())
            );
        }

        return false;
    }
}
