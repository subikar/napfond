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
 * Font Model
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Model
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Model_Font extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('gomage_designer/font');
    }

    public function getFontPath($fontPath)
    {
        $fontPath = str_replace($this->getConfig()->getBaseTmpMediaUrl(), '', $fontPath);
        $fontPath = str_replace($this->getConfig()->getBaseMediaUrl(), '', $fontPath);
        return $fontPath;
    }

    public function getUrl($fontPath)
    {
        return $this->getConfig()->getBaseTmpMediaUrl() . $fontPath;
    }

    public function getDestinationPath($fontPath)
    {
        return $this->getConfig()->getBaseMediaPath() . $fontPath;
    }

    public function getTempPath($fontPath)
    {
        return $this->getConfig()->getBaseTmpMediaPath() . $fontPath;
    }

    public function getDestinationDir($fontPath)
    {
        $expFontPath = explode('/', $fontPath);
        array_pop($expFontPath);
        $fontPath = implode('/', $expFontPath);
        if(strpos($fontPath, $this->getConfig()->getBaseMediaPath()) === false) {
            $fontPath = $this->getConfig()->getBaseMediaPath() . $fontPath;
        }

        return $fontPath;
    }

    public function loadFontByFile($fileName)
    {
        $fontData = $this->getResource()->getFontByName($fileName);
        if ($fontData) {
            $this->setData($fontData);
            return $this;
        }

        return false;
    }

    public function getConfig()
    {
        return Mage::getSingleton('gomage_designer/font_gallery_config');
    }
}