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
 * Design image model
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Model
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Model_Design_Image extends Mage_Core_Model_Abstract
{
    protected $_imageExtension = 'imagick';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('gomage_designer/design_image');
    }

    /**
     * Save design image
     *
     * @param array                      $image    Image data
     * @param int                        $imageId  Original image Id
     * @param Mage_Catalog_Model_Product $product  Product
     * @param int                        $designId Design Id
     * @return void
     */
    public function saveImage($image, $imageId, $product, $designId)
    {
        $dataHelper = Mage::helper('gomage_designer');
        $imageSettings = $dataHelper->getImageSettings($product, $imageId);
        if ($imageSettings) {
            $dimensions = $imageSettings['original_image']['dimensions'];
            $canvas = $this->createCanvas(
                $imageSettings['original_image']['path'], $dimensions[0], $dimensions[1]
            );
            if ($canvas) {
                $layer = $this->createLayer($image);
                if ($layer) {
                    $canvas = $this->addLayerToCanvas($canvas, $layer, $imageSettings);
                    $this->saveCanvas($canvas, $layer, $imageId, $designId);
                }
            }
        }
    }

    /**
     * Add Layer to canvas
     *
     * @param resource|Imagick $canvas        Canvas
     * @param resource|Imagick $layer         Layer
     * @param array            $imageSettings Image Settings
     * @return Imagick|resource
     */
    public function addLayerToCanvas($canvas, $layer, $imageSettings)
    {
        $designAreaLeft = $imageSettings['l'] - $imageSettings['w']/2;
        $designAreaTop = $imageSettings['t'] - $imageSettings['h']/2;
        $frameWidth = $dstWidth = $imageSettings['dimensions']['width'];
        $frameHeight = $dstHeight = $imageSettings['dimensions']['height'];
        $origImageWidth = $imageSettings['original_image']['dimensions'][0];
        $origImageHeight = $imageSettings['original_image']['dimensions'][1];
        if ($origImageWidth / $origImageHeight >= $frameWidth / $frameHeight) {
            $dstHeight = floor($frameWidth / $origImageWidth * $origImageHeight);
            $scale = $origImageWidth / $frameWidth;
        } else {
            $dstWidth = floor($frameHeight / $origImageHeight * $origImageWidth);
            $scale = $origImageHeight / $frameHeight;
        }
        $widthScale = $origImageWidth / $dstWidth;
        $heightScale = $origImageHeight / $dstHeight;
        $designAreaLeft = floor(($designAreaLeft * $scale) - ($frameWidth - $dstWidth));
        $designAreaTop = floor(($designAreaTop * $scale) - ($frameHeight - $dstHeight));
        if (extension_loaded($this->_imageExtension)) {
            $layer->resizeImage(
                floor($imageSettings['w'] * $widthScale),
                floor($imageSettings['h'] * $heightScale),
                Imagick::FILTER_LANCZOS,
                1
            );
            $canvas->compositeImage($layer, $layer->getImageCompose(), $designAreaLeft, $designAreaTop);
        } else {
            $layerWidth = floor(imagesx($layer) * $widthScale);
            $layerHeight = floor(imagesy($layer) * $heightScale);
            imagecopyresized(
                $canvas,
                $layer,
                $designAreaLeft,
                $designAreaTop,
                0,
                0,
                $layerWidth,
                $layerHeight,
                imagesx($layer),
                imagesy($layer)
            );
        }

        return $canvas;
    }

    /**
     * Create layer from image
     *
     * @param string $image Image
     * @return Imagick|resource
     */
    public function createLayer($image)
    {
        $image = base64_decode($image);
        if (extension_loaded($this->_imageExtension)) {
            $layer = new Imagick();
            $layer->readimageblob($image);
        } else {
            $layer = imagecreatefromstring($image);
        }

        return $layer;
    }

    /**
     * @param resource|Imagick $layer    Layer
     * @param string           $filename Filename
     */
    public function saveLayer($layer, $filename)
    {
        $size = $layer->getImageGeometry();
        $canvas = new Imagick();
        $canvas->newImage($size['width'], $size['height'], new ImagickPixel('white'));
        $canvas->setImageFormat($layer->getImageFormat());
        $canvas->compositeImage($layer, imagick::COMPOSITE_OVER, 0, 0);
        $resolution = $this->_getImageExtensionForSave() == 'pdf' ? 300 : 600;
        $canvas->setImageResolution($resolution, $resolution);
        $canvas->writeImage($filename);
    }
    /**
     * Create canvas
     *
     * @param string $image Image Path
     * @param float  $width       Width
     * @param float  $height      Height
     * @return Imagick|resource|boolean
     */
    public function createCanvas($image, $width, $height)
    {
        $width = (int) $width;
        $height = (int) $height;
        if ($width > 0 && $height > 0) {
            if (extension_loaded($this->_imageExtension)) {
                $canvas = new Imagick();
                $canvas->setsize($width, $height);
                $canvas->readimage($image);
                $resolution = $this->_getImageExtensionForSave() == 'pdf' ? 300 : 600;
                $canvas->setImageResolution($resolution, $resolution);
                $canvas->setImageFormat($this->_getImageExtensionForSave());
            } else {
                $imageExtension = $this->_prepareImageExtension($image);
                $imageCreateFunction = 'imagecreatefrom' . $imageExtension;
                if (function_exists($imageCreateFunction)) {
                    $canvas = imagecreatetruecolor($width, $height);
                    $srcImage = $imageCreateFunction($image);
                    imagecopyresampled($canvas,$srcImage, 0, 0, 0, 0, $width, $height, $width, $height);
                }
            }

            return $canvas;
        }

        return false;
    }

    /**
     * Save file to DB
     *
     * @param Imagick $canvas   canvas
     * @param int     $imageId  Image Id
     * @param int     $designId Design Id
     * @return void
     */
    public function saveCanvas($canvas, $layer, $imageId, $designId)
    {
        $currentProduct = Mage::registry('product');
        $designConfig = Mage::getSingleton('gomage_designer/design_config');
        $configPath = $designConfig->getBaseMediaPath();

        if($currentProduct && $currentProduct->getId()) {
            $fileToSave = $this->_prepareFileForSave();
            $layerFilename = $this->_prepareFileForSave();
            if (extension_loaded($this->_imageExtension)){
                $this->saveLayer($layer, $layerFilename);
                $canvas->writeImage($fileToSave);
                if ($this->_getImageExtensionForSave() == 'pdf') {
                    $fileToSaveJpg = str_replace('.pdf', '.jpg', $fileToSave);
                    $layerFilenameJpg = str_replace('.pdf', '.jpg', $layerFilename);
                    $canvas->setImageFormat('jpg');
                    $canvas->writeImage($fileToSaveJpg);
                    $layer->setImageFormat('jpg');
                    $this->saveLayer($layer, $layerFilenameJpg);
                }
                $layer->destroy();
                $canvas->destroy();
            } else {
                $imageExtension = strtolower(pathinfo($fileToSave, PATHINFO_EXTENSION));
                if ($imageExtension == 'pdf') {
                    $fileToSave = str_replace($imageExtension, '', $fileToSave);
                    $layerFilename = str_replace($imageExtension, '', $layerFilename);
                    imagejpeg($canvas, $fileToSave . 'jpg', 100);
                    imagejpeg($layer, $layerFilename . 'jpg', 100);
                    imagedestroy($canvas);
                    imagedestroy($layer);
                    $this->_createPdfImage($fileToSave);
                    $this->_createPdfImage($layerFilename);
                } else {
                    if ($imageExtension = 'jpg') {
                        $saveFunction = 'imagejpeg';
                    } else {
                        $saveFunction = 'image' . $imageExtension;
                    }
                    if (function_exists($saveFunction)) {
                        $saveFunction($canvas, $fileToSave, 100);
                        $saveFunction($layer, $layerFilename, 100);
                        $this->_setImageResolution($fileToSave);
                        $this->_setImageResolution($layerFilename);
                        imagedestroy($canvas);
                        imagedestroy($layer);
                    }
                }
            }

            $this->addData(array(
                'product_id' => $currentProduct->getId(),
                'design_id' => $designId,
                'image' => str_replace($configPath, '', $fileToSave),
                'layer' => str_replace($configPath, '', $layerFilename),
                'image_id' => $imageId,
                'created_date' => Mage::getModel('core/date')->gmtDate(),
            ))->save();
        }
    }

    protected function _createPdfImage($filename)
    {
        $pdf = new Zend_Pdf();
        $image = Zend_Pdf_Image::imageWithPath($filename . 'jpg');
        $pdfPage = $pdf->newPage($image->getPixelWidth(). ':'. $image->getPixelHeight());
        $pdfPage->drawImage($image, 0, 0, $image->getPixelWidth(), $image->getPixelHeight());
        $pdf->pages[] = $pdfPage;
        $fileToSave = $filename.'pdf';
        $pdf->save($fileToSave);
    }

    protected function _setImageResolution($file)
    {
        $image = file_get_contents($file);
        $image = substr_replace($image, pack("Cnn", 0x01, 300, 300), 13, 5);
        file_put_contents($file, $image);
    }

    /**
     * Prepare file for save
     *
     * @return string
     */
    protected function _prepareFileForSave()
    {
        $pathToSave = $this->_preparePathToSave();
        $imageExtension = $this->_getImageExtensionForSave();

        $fileName = Mage::helper('core')->getRandomString(16) . '.' . $imageExtension;

        return $pathToSave . DS . $fileName;
    }

    /**
     * Prepare path for save
     *
     * @return string
     */
    protected function _preparePathToSave()
    {
        $pathToSave = Mage::getSingleton('gomage_designer/design_config')->getBaseMediaPath();
        $this->mkDirIfNotExists($pathToSave);

        return $pathToSave;
    }

    /**
     * Create directory if not exist
     *
     * @param string $directory Directory path
     * @return void
     */
    public function mkDirIfNotExists($directory)
    {
        if(!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
    }

    /**
     * Return image extension
     *
     * @param string $imagePath Image path
     * @return string
     */
    protected function _prepareImageExtension($imagePath)
    {
        $imageExtension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
        if(in_array($imageExtension, array('jpg', 'jpeg'))) {
            $imageExtension = 'jpeg';
        }
        return $imageExtension;
    }

    protected function _getImageExtensionForSave()
    {
        return Mage::getStoreConfig('gomage_designer/general/format');
    }
}
