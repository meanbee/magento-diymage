<?php
class Meanbee_Diy_Model_Cache {
    
    private $__tags = array("meanbee/diy");
    private $__key_license = "license";
    
    protected function _getCache() {
        return Mage::getSingleton('core/cache');
    }
    
    public function getLicenseStatus() {
        return $this->_getCache()->load($this->__key_license);
    }
    
    public function setLicenseStatus($status) {
        $this->_getCache()->save($status, $this->__key_license, $this->__tags, 60*60*24*7);
    }
}