<?php
// {{license}}
class Meanbee_Diy_Model_Feed extends Mage_AdminNotification_Model_Feed {
    const CACHE_KEY = 'diy_notifications_lastcheck';

    public function getFeedUrl() {
        return Mage::getSingleton('diy/config')->getNotificationsUrl();
    }
    
    public function getLastUpdate() {
        return Mage::app()->loadCache(self::CACHE_KEY);
    }
    
    public function setLastUpdate() {
        Mage::app()->saveCache(time(), self::CACHE_KEY);
        return $this;
    }
}