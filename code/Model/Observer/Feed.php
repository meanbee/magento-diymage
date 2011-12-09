<?php
// {{license}}
class Meanbee_Diy_Model_Observer_Feed implements Meanbee_Diy_Model_Observer_Interface {
    protected $_log;
    protected $_paths = array();
    
    public function __construct() {
        $this->_log = Mage::getModel('diy/log');
    }
    
    public function observe(Varien_Event_Observer $observer) {
        $event = $observer->getEvent();
        
        if (Mage::getSingleton('admin/session')->isLoggedIn()) {
            $feed = Mage::getModel('diy/feed');
            $feed->checkUpdate();
        }
    }
}
