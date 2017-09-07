<?php
class  EaDesign_PdfGenerator_Model_Eacore_Observer
{
    public function preDispatch(Varien_Event_Observer $observer)
    {
        if (Mage::getSingleton('admin/session')->isLoggedIn()) {

            $feedModel  = Mage::getModel('eadesign/eacore_feed');

            $feedModel->checkUpdate();
        }
    }
}