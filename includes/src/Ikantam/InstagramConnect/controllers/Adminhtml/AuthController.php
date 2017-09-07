<?php
/**
 * iKantam
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade InstagramConnect to newer
 * versions in the future.
 *
 * @category    Ikantam
 * @package     Ikantam_InstagramConnect
 * @copyright   Copyright (c) 2012 iKantam LLC (http://www.ikantam.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Ikantam_InstagramConnect_Adminhtml_AuthController extends Mage_Adminhtml_Controller_Action
{

    const INSTAGRAM_AUTH_URL = 'https://api.instagram.com/oauth/authorize/';
    const INSTAGRAM_ACCESSS_TOKEN_URL = 'https://api.instagram.com/oauth/access_token';

    const INSTAGRAM_SESSION_DATA_KEY = 'instagram_session_data';
    const INSTAGRAM_CONFIG_DATA_KEY = 'ikantam/instagram_connect/instagram_data';

    public function preDispatch()
    {
        Mage::getSingleton('adminhtml/url')->turnOffSecretKey();
        parent::preDispatch();
    }

	public function indexAction()
    {
        $this->_redirectUrl($this->_getAuthUrl());
    }

    public function callbackAction()
    {
        $code = $this->getRequest()->getParam('code');
        $response = $this->_getAccessToken($code);
        $responseObject = json_decode($response);

        /** @var $session Mage_Core_Model_Session  */
        $session = Mage::getModel('core/session');
        $session->setData(self::INSTAGRAM_SESSION_DATA_KEY, $responseObject);

        Mage::getConfig()->saveConfig(self::INSTAGRAM_CONFIG_DATA_KEY, serialize($responseObject), 'default', 0);

        $redirectUrl = Mage::helper('instagramconnect')->getAdminConfigSectionUrl();
        $this->_redirectUrl($redirectUrl);
    }

    protected function _getAccessToken($code)
    {
        $postParams = $this->_getInstagamHelper()->buildUrl(
            null,
            array(
                'client_id' => $this->_getClientId(),
                'client_secret' => $this->_getClientSecret(),
                'grant_type'    => 'authorization_code',
                'redirect_uri'  => $this->_getAuthRedirectUri(),
                'code'          => $code
            )
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::INSTAGRAM_ACCESSS_TOKEN_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $output = curl_exec($ch);
        curl_close($ch);
        return $output;

    }

    /**
     * Get url for authentification on Instagram
     * @return string
     */
    protected function _getAuthUrl()
    {
        /** @var $url  */
         $url = $this->_getInstagamHelper()->buildUrl(
             self::INSTAGRAM_AUTH_URL,
             array(
                 'client_id'    => $this->_getClientId(),
                 'redirect_uri' => $this->_getAuthRedirectUri(),
                 'response_type'=> 'code'
             )
         );

        return $url;
    }

    protected function _getAuthRedirectUri()
    {
        return Mage::getUrl('instagramconnect/auth');
    }

    protected function _getClientId()
    {
        return Mage::getStoreConfig('ikantam_instagramconnect/module_options/client_id');
    }


    protected function _getClientSecret()
    {
        return Mage::getStoreConfig('ikantam_instagramconnect/module_options/client_secret');
    }

    /**
     * @return Ikantam_InstagramConnect_Helper_Data
     */
    protected function _getInstagamHelper()
    {
        return Mage::helper('instagramconnect');
    }

}
