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
class Ikantam_InstagramConnect_Adminhtml_InstagramconnectController extends Mage_Adminhtml_Controller_Action
{
    const UPDATE_TYPE_USER  = 1;
    const UPDATE_TYPE_TAG   = 0;


	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("Title"));
	   $this->renderLayout();
    }
    
    public function updateAction()
    {
        $updateType = Mage::getStoreConfig('ikantam_instagramconnect/module_options/updatetype');

        switch($updateType){

            case self::UPDATE_TYPE_TAG :
                $result = Mage::helper('instagramconnect/image')->update();
                $message = $this->__('An error occured. Make sure you are authenticated with Instagram.');
                if(!$result){
                    Mage::getSingleton('adminhtml/session')->addError($message);
                }
                break;

            case self::UPDATE_TYPE_USER :
                if( !Mage::getModel('instagramconnect/instagramauth')->isValid() ){
                    $message = $this->__('Need Instagram user authentification');
                    Mage::getSingleton('adminhtml/session')->addError($message);
                    break;
                }

                $result = Mage::helper('instagramconnect/image_user')->update();
                $message = $this->__('An error occured. Make sure you are authenticated with Instagram.');
                if(!$result){
                    Mage::getSingleton('adminhtml/session')->addError($message);
                }

                break;

        }

        $this->_redirect('instagramconnect/adminhtml_instagramconnect/new');
    }
    
    public function newAction()
    {
    	$this->loadLayout();
	   	$this->_title($this->__("New Images"));
	   	$this->renderLayout();
    }
    
    public function approvedAction()
    {
    	$this->loadLayout();
	   	$this->_title($this->__("Approved Images"));
	   	$this->renderLayout();
    }
    
    public function approveAction()
    {
    	$imageId = $this->getRequest()->getPost('id');
    	
    	$image = Mage::getModel('instagramconnect/instagramimage')->load($imageId);
    	
    	if ($image->getId()) {
    		$image->setIsApproved(1)->save();
    	}
    	
    	$this->getResponse()->setBody(json_encode(array('success' => true)));
    }
    
    public function deleteAction()
    {
    	$imageId = $this->getRequest()->getPost('id');
    	
    	$image = Mage::getModel('instagramconnect/instagramimage')->load($imageId);
    	
    	if ($image->getId()) {
    		$image->setIsVisible(0)->save();
    	}
    	
    	$this->getResponse()->setBody(json_encode(array('success' => true)));
    }
}
