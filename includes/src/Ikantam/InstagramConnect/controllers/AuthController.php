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
class Ikantam_InstagramConnect_AuthController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    {

        //@TODO check isAdmin login
        $code = $this->getRequest()->getQuery('code');

        $adminUrl = Mage::helper("adminhtml")
            ->getUrl("instagramconnect/adminhtml_auth/callback", array( 'code' => $code ));

        $this->_redirectUrl($adminUrl);
        return;

    }

}
