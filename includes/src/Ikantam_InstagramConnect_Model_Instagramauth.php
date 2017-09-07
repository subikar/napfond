<?php
/**
 * iKantam LLC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the iKantam EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://magento.ikantam.com/store/license-agreement
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade
 * HouseConfigurator Module to newer versions in the future.
 *
 * @category   Ikantam
 * @package    Ikantam_HouseConfigurator
 * @author     iKantam Team <support@ikantam.com>
 * @copyright  Copyright (c) 2012 iKantam LLC (http://www.ikantam.com)
 * @license    http://magento.ikantam.com/store/license-agreement  iKantam EULA
 */
class Ikantam_InstagramConnect_Model_Instagramauth
{
    const INSTAGRAM_SESSION_DATA_KEY = 'instagram_session_data';
    const INSTAGRAM_CONFIG_DATA_KEY = 'ikantam/instagram_connect/instagram_data';
    
    public function getUserData()
    {
        /** @var $session Mage_Core_Model_Session  */
        $session = Mage::getModel('core/session');
        $info = $session->getData('instagram_session_data');
   
        if (!$info) {
            $configDataKey = self::INSTAGRAM_CONFIG_DATA_KEY;
               
            $info = unserialize(Mage::getStoreConfig($configDataKey, 0));
        }

        return $info;
    }

    public function isValid()
    {
        $configDataKey = self::INSTAGRAM_CONFIG_DATA_KEY;
        return (!!$this->getUserData() || Mage::getStoreConfig($configDataKey, 0));
    }

    public function getAccessToken()
    {
        return $this->getUserData()->access_token;
    }

}
