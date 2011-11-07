<?php
// {{license}}
class Meanbee_Diy_Model_Config {
    public function isEnabled() {
        return Mage::getStoreConfig('diy/general/enabled');
    }
    
    public function isLoggingEnabled() {
        return $this->isEnabled() && Mage::getStoreConfig('diy/general/log_enabled');
    }
    
    public function isDeveloperMode() {
        return $this->isEnabled() && Mage::getStoreConfig('diy/general/developer_enabled');
    }
    
    public function getLicenseEmail() {
        return Mage::getStoreConfig('diy/license/email');
    }
    
    public function hasCompletedLicenseFields() {
        return $this->getLicenseEmail();
    }
    
    public function getVersion() {
        return Mage::getConfig()->getNode('modules/Meanbee_Diy/version')->__toString();
    }
    
    public function getPingUrl() {
        return "http://ping.diymage.com";
    }
    
    public function getNotificationsUrl() {
        return "http://notifications.diymage.com";
    }
    
    public function getLogName() {
        return "diymage.log";
    }
}