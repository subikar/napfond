<?php
class Kojoman_Twilio_Model_Twilio  extends Mage_Core_Model_Abstract
{
 public function _construct()
    {
        $this->_init('twilio/twilio','sms_id');
		parent::_construct();
    }
}