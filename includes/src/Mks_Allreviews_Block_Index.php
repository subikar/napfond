<?php   
class Mks_Allreviews_Block_Index extends Mage_Core_Block_Template{   


public function getAllreviewsStatus()
    {
        return Mage::getStoreConfig('reviewssection/reviewsgroup/status',Mage::app()->getStore());
    }

public function getAllreviewsNumberofreviews()
    {
        return Mage::getStoreConfig('reviewssection/reviewsgroupsetting/numberofreviews',Mage::app()->getStore());
    }
public function getAllreviewsNumberofcolumns()
    {
        return Mage::getStoreConfig('reviewssection/reviewsgroupsetting/numberofcolumns',Mage::app()->getStore());
    }
public function getAllreviewsDisplayproductimages()
    {
        return Mage::getStoreConfig('reviewssection/reviewsgroupsetting/displayproductimages',Mage::app()->getStore());
    }
public function getAllreviewsEnableproducturl()
    {
        return Mage::getStoreConfig('reviewssection/reviewsgroupsetting/enableproducturl',Mage::app()->getStore());
    }
public function getAllreviewsDisplayreviewstitle()
    {
        return Mage::getStoreConfig('reviewssection/reviewsgroupsetting/displayreviewstitle',Mage::app()->getStore());
    }
public function getAllreviewsDisplayreviewsdate()
    {
        return Mage::getStoreConfig('reviewssection/reviewsgroupsetting/displayreviewsdate',Mage::app()->getStore());
    }
public function getAllreviewsDisplayreviewsstars()
    {
        return Mage::getStoreConfig('reviewssection/reviewsgroupsetting/displayreviewsstars',Mage::app()->getStore());
    }
public function getAllreviewsDisplayreviewscomments()
    {
        return Mage::getStoreConfig('reviewssection/reviewsgroupsetting/displayreviewscomments',Mage::app()->getStore());
    }
public function getAllreviewsFilteringtab()
    {
        return Mage::getStoreConfig('reviewssection/reviewsgroupsetting/filteringtab',Mage::app()->getStore());
    }

public function getAllreviewsshowreview()
    {
        return Mage::getStoreConfig('reviewssection/reviewsgroupsetting/showreview',Mage::app()->getStore());
    }
public function getAllreviewsdisplaynickname()
    {
        return Mage::getStoreConfig('reviewssection/reviewsgroupsetting/displaynickname',Mage::app()->getStore());
    }


}
