<?php
// {{license}}
class Meanbee_Diy_Model_Feed extends Mage_AdminNotification_Model_Feed {
    const CACHE_KEY = 'diy_notifications_lastcheck';

    public function getFeedUrl() {
        return Mage::getSingleton('diy/config')->getNotificationsUrl();
    }
    
    public function getLastUpdate() {
        $cache = $this->_getCache();
        return $cache->load($cache::KEY_NOTIFY);
    }
    
    public function setLastUpdate() {
        $cache = $this->_getCache();
        $cache->save($cache::KEY_NOTIFY, time());
        return $this;
    }
    
    protected function _getCache() {
        return Mage::getSingleton('diy/cache');
    }
}