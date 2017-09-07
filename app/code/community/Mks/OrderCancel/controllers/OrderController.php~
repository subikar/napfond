<?php
/**
 * Mks Soft
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Mks
 * @package    Mks_OrderCancel
 * @copyright  Copyright (c) 2011 Mks Soft (mks.soft@gmail.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * OrderCancel controller class
 */
class Mks_OrderCancel_OrderController extends Mage_Core_Controller_Front_Action
{
    /**
     * Handles cancel order action
     */
    public function cancelAction()
    {
        $orderId = $this->getRequest()->get('order_id');
        
        // Load order
        if(!empty($orderId)) {
            $order = Mage::getModel('sales/order')->load($orderId);
            $order->cancel();
            $order->save();
            Mage::getSingleton('core/session')->addSuccess($this->__("Order was successfully cancelled."));
        }
        
        // Redirect back to orders list
        $this->_redirect('sales/order/history/');
    }
}
