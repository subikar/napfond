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
class Ikantam_InstagramConnect_Helper_Image_User extends Ikantam_InstagramConnect_Helper_Image
{
    const INSTAGRAM_API_USER_MEDIA_URL = 'https://api.instagram.com/v1/users/%userId%/media/recent/';
    const INSTAGRAM_API_USER_ID_URL = 'https://api.instagram.com/v1/users/search?q=%userId%';

    public function update()
    {
        $responseStatus = true;
        foreach ($this->getUsers(false) as $userId) {
            $user_id = $this->getUserId($userId);
            if ($user_id) {
                $endpointUrl = $this->getEndpointUrl($user_id);
            } else {
                $endpointUrl = $this->getEndpointUrl($userId);
            }

            $response = $this->getImages($endpointUrl, '@' . $userId);

            if(isset($response['error'])){
                $responseStatus = false;
            }
        }
        return $responseStatus;
    }
    
    public function getUserId($userId)
    {
        $url = $this->getSearchEndpointUrl($userId);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($ch);
        // echo curl_error($ch);
        curl_close($ch);

        $data = json_decode($output);

        if (isset($data->data)) {
            $data2 = $data->data;
            if (isset($data2[0])) {
                $data3 = $data2[0];
                if (isset($data3->id)) {
                    return $data3->id;
                }
            }
        }

        return null;
    }

    /**
     * Get array of users, defined in config panels
     * @return array
     */
    public function getUsers($withPrefix = true)
    {
        $rawUsers = Mage::getStoreConfig('ikantam_instagramconnect/module_options/users');
        $users = explode(',', $rawUsers);

        $out = array();
        foreach ($users as $user) {
            $user = ltrim(trim($user), '@');
            if (!empty($user)) {
                if($withPrefix){
                    $out[] = '@' . $user;
                } else {
                    $out[] = $user;
                }
            }
        }
        return $out;
    }

    public function getSearchEndpointUrl($userId)
    {
        $endpointUrl = str_replace('%userId%', $userId, self::INSTAGRAM_API_USER_ID_URL);
        /** @var $helper Ikantam_InstagramConnect_Helper_Data */
        $helper = Mage::helper('instagramconnect');
        $accessToken = Mage::getModel('instagramconnect/instagramauth')->getAccessToken();
        $endpointUrl = $endpointUrl . '&access_token=' . $accessToken;

        return $endpointUrl;
    }

    public function getEndpointUrl($userId)
    {
        $endpointUrl = str_replace('%userId%', $userId, self::INSTAGRAM_API_USER_MEDIA_URL);
        /** @var $helper Ikantam_InstagramConnect_Helper_Data */
        $helper = Mage::helper('instagramconnect');
        $accessToken = Mage::getModel('instagramconnect/instagramauth')->getAccessToken();
        $endpointUrl = $helper->buildUrl($endpointUrl, array(
            'access_token'  => $accessToken,
        ));

        return $endpointUrl;
    }

    protected function getImages($endpointUrl, $tag)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpointUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($ch);
        // echo curl_error($ch);
        curl_close($ch);

        $data = json_decode($output);

        unset($output);

        $out = array();

        if (!isset($data->meta, $data->meta->code) || $data->meta->code !== 200) {
            $out['error'] = $this->__("Instagram connect error");
            return $out;
        }

        if (!isset($data->data)) {
            $out['error'] = $this->__("Instagram data not founded");
            return $out;
        }

        if (isset($data->pagination->next_url)) {
            $out['nextUrl'] = $data->pagination->next_url;
        }

        if (isset($data->pagination->next_max_tag_id)) {
            $out['nextMaxId'] = $data->pagination->next_max_tag_id;
        }

        foreach ($data->data as $item)
        {
            $captionText = '';
            if($item->caption){
                $captionText = $item->caption->text;
            }

            $username    = $item->user->username;

            $standardResolutionUrl = $item->images->standard_resolution->url;
            $lowResolutionUrl      = $item->images->low_resolution->url;
            $thumbnailUrl          = $item->images->thumbnail->url;
            $systemId              = $item->id;

            $image = Mage::getModel('instagramconnect/instagramimage');

            $image->setStandardResolutionUrl($standardResolutionUrl)
                ->setLowResolutionUrl($lowResolutionUrl)
                ->setThumbnailUrl($thumbnailUrl)
                ->setImageId($systemId)
                ->setUsername($username)
                ->setCaptionText($captionText)
                ->setTag($tag)
                ->save();
        }

        return $out;
    }

}
