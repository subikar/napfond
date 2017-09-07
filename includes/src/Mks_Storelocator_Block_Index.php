<?php   
class Mks_Storelocator_Block_Index extends Mage_Core_Block_Template{   



public function getStorelocatorEnabled()
    {
        return Mage::getStoreConfig('storelocatorcode/storeloactorsetting/enable',Mage::app()->getStore());
    }
public function getStorelocatorName()
    {
        return Mage::getStoreConfig('storelocatorcode/storeloactorfields/name',Mage::app()->getStore());
    }
public function getStorelocatorAddress()
    {
        return Mage::getStoreConfig('storelocatorcode/storeloactorfields/address',Mage::app()->getStore());
    }
public function getStorelocatorZipcode()
    {
        return Mage::getStoreConfig('storelocatorcode/storeloactorfields/zipcode',Mage::app()->getStore());
    }
public function getStorelocatorCity()
    {
        return Mage::getStoreConfig('storelocatorcode/storeloactorfields/city',Mage::app()->getStore());
    }
public function getStorelocatorCountry()
    {
        return Mage::getStoreConfig('storelocatorcode/storeloactorfields/Country',Mage::app()->getStore());
    }
public function getStorelocatorPhone()
    {
        return Mage::getStoreConfig('storelocatorcode/storeloactorfields/phone',Mage::app()->getStore());
    }
public function getStorelocatorFax()
    {
        return Mage::getStoreConfig('storelocatorcode/storeloactorfields/fax',Mage::app()->getStore());
    }
public function getStorelocatorDescription()
    {
        return Mage::getStoreConfig('storelocatorcode/storeloactorfields/description',Mage::app()->getStore());
    }
public function getStorelocatorStoreurl()
    {
        return Mage::getStoreConfig('storelocatorcode/storeloactorfields/storeurl',Mage::app()->getStore());
    }
public function getStorelocatorEmail()
    {
        return Mage::getStoreConfig('storelocatorcode/storeloactorfields/email',Mage::app()->getStore());
    }
public function getStorelocatorTradinghours()
    {
        return Mage::getStoreConfig('storelocatorcode/storeloactorfields/tradinghours',Mage::app()->getStore());
    }
public function getStorelocatorImage()
    {
        return Mage::getStoreConfig('storelocatorcode/storeloactorfields/image',Mage::app()->getStore());
    }


}
