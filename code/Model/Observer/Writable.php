<?php
// {{license}}
class Meanbee_Diy_Model_Observer_Writable implements Meanbee_Diy_Model_Observer_Interface {
    protected $_log;
    protected $_paths = array();
    
    public function __construct() {
        $this->_log = Mage::getModel('diy/log');
        
        $this->_paths[] = Mage::getBaseDir() . "/skin/frontend/base/default/";
    }
    
    public function observe(Varien_Event_Observer $observer) {
        $event = $observer->getEvent();
        $form = $event->getForm();
      
        if (count($this->_paths) > 0) {
            $failed_paths = array();
            
            foreach ($this->_paths as $path) {
                if (!is_writable($path)) {
                    $failed_paths[] = $path;
                }
            }
            
            if (count($failed_paths) > 0) {
                $message = "The following paths aren't writable, but need to be: <tt>" . join ("</tt>, <tt>", $failed_paths) . "</tt>.";
                Mage::getSingleton('core/session')->addNotice("DIY Mage: $message");
            }
        }
    }
}
