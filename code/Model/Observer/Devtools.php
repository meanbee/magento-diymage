<?php
// {{license}}
class Meanbee_Diy_Model_Observer_Devtools implements Meanbee_Diy_Model_Observer_Interface {
    protected $_log;
    
    public function __construct() {
        $this->_log = Mage::getModel('diy/log');
    }
    
    /**
     * @param Varien_Event_Observer $observer 
     * @return void
     * @author Nicholas Jones
     */
    public function observe(Varien_Event_Observer $observer) {
        if (!Mage::getSingleton('diy/config')->isEnabled()) {
            return;
        }
        
        $block = Mage::app()->getLayout()->createBlock('diy/devtools');
        Mage::app()->getLayout()->getBlock('footer')->append($block);
    }
}