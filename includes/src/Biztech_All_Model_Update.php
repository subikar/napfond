<?php

    class Biztech_All_Model_Update extends Mage_Core_Helper_Abstract
    {

        const UPDATE_NOTIFICATION_FEED_URL = 'http://store.biztechconsultancy.com/rss/catalog/new/store_id/1/';
        public function getInterests()
        {
            $types = @explode(',', Mage::getStoreConfig('all/all_general/interests'));
            return $types;
        }
        public function isExtensionInstalled($code)
        {

            $modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());
            foreach ($modules as $moduleName) {
                if ($moduleName == $code) {
                    return true;
                }
            }
            return false;
        }

        public function check()
        {
            if ((time() - Mage::app()->loadCache('biztech_all_updates_feed_lastcheck')) > Mage::getStoreConfig('all/all_general/check_frequency')) {
                $this->refresh();
            }
        }

        public function refresh()
        {
            $types               = array();
            $feedData           = array();
            $extensio_module    = array();
            try{
                $xml = false;
                $limit_context = stream_context_create(array('http'=>array('timeout' => 5)));
                $xml            =   file_get_contents(self::UPDATE_NOTIFICATION_FEED_URL,true,$limit_context);   
                $xml            =   simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);//LIBXML_NOCDATA LIBXML_NOWARNING 

                if($xml != false){
                    $interests                  = $this->getInterests();
                    if(!empty($interests[0])){
                        $isInterestedInSelfUpgrades = in_array(Biztech_All_Model_Source_Updates_Type::TYPE_INSTALLED_UPDATE, $interests);    
                        $isInterestedInAllUpgrades = in_array(Biztech_All_Model_Source_Updates_Type::TYPE_UPDATE_RELEASE, $interests);    
                        $modules                    = Mage::getConfig()->getNode('modules')->children();
                        $modulesArray               = (array)$modules;
                        foreach($xml->channel->item as $keys=>$extensions) {
                            $types[]                =   (string) $extensions->update_notifications->type;
                            $extensions_name      = (string)$extensions->update_notifications->extensions;

                            if((string)$extensions->update_notifications->date!=""&&(string)$extensions->update_notifications->extension_title!=""&&(string)$extensions->update_notifications->extension_content!=""&&(string)$extensions->update_notifications->product_url!=""){
                                if(!$isInterestedInAllUpgrades){
                                    if ($this->isExtensionInstalled($extensions_name)) {
                                        $feedData[] = array(
                                            'severity' => 4,
                                            'date_added' =>  (string) $extensions->update_notifications->date,
                                            'title' => (string)$extensions->update_notifications->extension_title,
                                            'description' => (string)$extensions->update_notifications->extension_content,
                                            'url' => (string)$extensions->update_notifications->product_url,
                                        );
                                    }           
                                } else{
                                    $feedData[] = array( 
                                        'severity' => 4,
                                        'date_added' =>  (string)$extensions->update_notifications->date,
                                        'title' => (string)$extensions->update_notifications->extension_title,
                                        'description' => (string)$extensions->update_notifications->extension_content,
                                        'url' => (string)$extensions->update_notifications->product_url,
                                    );
                                }
                            }
                        }
                        if ($feedData) {
                            foreach($feedData as $data){
                                if ((array_intersect($types,$interests) && $isInterestedInSelfUpgrades)) {
                                    Mage::getModel('adminnotification/inbox')->parse(array_reverse(array($data)));
                                }
                            }
                        } 
                        Mage::app()->saveCache(time(), 'biztech_all_updates_feed_lastcheck');
                        return true; 
                    }
                } else{
                    return false;
                }

            }catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
}