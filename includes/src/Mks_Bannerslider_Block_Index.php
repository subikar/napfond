<?php   
class Mks_Bannerslider_Block_Index extends Mage_Core_Block_Template{   


public function getImagegalleryEnabled()
    {
        return Mage::getStoreConfig('imagegallerysection/imagegallerygroup/enable',Mage::app()->getStore());
    }

public function getImagegalleryPaggingstart()
    {
        return Mage::getStoreConfig('imagegallerysection/imagegallerygroup/paggingstart',Mage::app()->getStore());
    }

public function getImagegalleryRowitem()
    {
        return Mage::getStoreConfig('imagegallerysection/imagegallerygroup/rowitem',Mage::app()->getStore());
    }

 

}
