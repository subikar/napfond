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
 * Ajax helper
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Helper
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Helper_Ajax extends Mage_Core_Helper_Data
{
    public function sendResponse(GoMage_ProductDesigner_Model_Ajax_Response $response)
    {
        Mage::app()->getResponse()->setHeader('Content-type', 'application/json');
        return Mage::app()->getResponse()->setBody($response->asJson());
    }

    public function sendError($message)
    {
        return $this->sendResponse(
            Mage::getModel('gomage_designer/ajax_response')->error()->setMessage($message)
        );
    }

    public function sendSuccess($data = array())
    {
        return $this->sendResponse(
            Mage::getModel('gomage_designer/ajax_response')->success()->addData($data)
        );
    }

    public function sendRedirect($data = array())
    {
        return $this->sendResponse(
            Mage::getModel('gomage_designer/ajax_response')->redirect()->addData($data)
        );
    }
}
