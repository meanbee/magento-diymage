<?php
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
    
    public function getLogName() {
        return "diymage.log";
    }
}