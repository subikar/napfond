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
 * Designer image helper abstract
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Helper
 * @author     Roman Bublik <rb@gomage.com>
 */
abstract class GoMage_ProductDesigner_Helper_Image_Abstract extends Mage_Core_Helper_Data
{
    /**
     * Thumbnail filename
     *
     * @var string
     */
    protected $_filename;

    /**
     * Files base dir
     *
     * @var mixed
     */
    protected $_baseDir;

    /**
     * Keep aspect ratio flag
     *
     * @var bool
     */
    protected $_keepAspectRatioFlag = true;

    protected $_watermarkSize;

    /**
     * Init base image
     *
     * @param string $filename Filename
     * @return GoMage_ProductDesigner_Helper_Image
     */
    abstract public function init($filename);

    /**
     * Get cached file url
     *
     * @return string
     */
    abstract protected function _getCachedUrl();

    /**
     * Get cache dir
     *
     * @return string
     */
    abstract protected function _getCacheDir();

    /**
     * Resize the image
     *
     * @param integer $width  Width
     * @param integer $height Height
     * @return GoMage_ProductDesigner_Helper_Image
     */
    public function resize($width, $height = null)
    {
        $this->_width = $width;
        $this->_height = $height;
        return $this;
    }

    public function setWatermarkSize($size)
    {
        $this->_watermarkSize = $size;
        return $this;
    }

    /**
     * Set keep aspect ratio flag
     *
     * @param bool $flag Flag
     * @return GoMage_ProductDesigner_Helper_Image
     */
    public function keepAspectRatio($flag)
    {
        $this->_keepAspectRatioFlag = $flag;
        return $this;
    }

    /**
     * Render the image path
     *
     * @return string
     */
    public function __toString()
    {
        if (file_exists($this->_getCachedFilePath())) {
            return $this->_getCachedUrl();
        }
        $this->_cacheFile();
        return $this->_getCachedUrl();
    }

    /**
     * Get original file path
     *
     * @return string
     */
    protected function _getOriginalFilePath()
    {
        return $this->_baseDir . $this->_filename;
    }

    /**
     * Get cached file path
     *
     * @return void
     */
    protected function _getCachedFilePath()
    {
        return $this->_getCacheDir() . DS . $this->_filename;
    }

    /**
     * Get image height
     *
     * @return int
     */
    public function getHeight()
    {
        $size = $this->getImageDimensions();
        if (isset($size[1])) {
            return $size[1];
        }
        return 0;
    }

    /**
     * Get image dimensions
     *
     * @return array
     */
    public function getImageDimensions()
    {
        if (!file_exists($this->_getCachedFilePath())) {
            $this->_cacheFile();
        }
        if ($size = getimagesize($this->_getCachedFilePath())) {
            return $size;
        }
        return array();
    }

    /**
     * Cache file
     *
     * @return string
     */
    protected function _cacheFile()
    {
        try {
            $image = new Varien_Image($this->_getOriginalFilePath());
            if ($this->_width || $this->_height) {
                $image->quality(100);
                $image->keepTransparency(1);
                $image->keepAspectRatio($this->_keepAspectRatioFlag);
                $image->keepFrame(true);
                $image->backgroundColor(array(255, 255, 255));
                $image->resize($this->_width, $this->_height);

                $path = $this->_getCacheDir() . $this->_filename;
                $dir = dirname($path);
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                $image->save($this->_getCacheDir(), $this->_filename);
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }
}
