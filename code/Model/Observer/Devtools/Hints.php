<?php
// {{license}}
class Meanbee_Diy_Model_Observer_Devtools_Hints implements Meanbee_Diy_Model_Observer_Interface {
    
    const EVENT_BEFORE = 'core_block_abstract_to_html_before';
    const EVENT_AFTER  = 'core_block_abstract_to_html_after';

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
        $event = $observer->getEvent();
        
        $event_handle = $event->getName();
        $block = $event->getBlock();
        
        $type = $block->getType();
        $template = $block->getTemplate();
        
        switch ($event_handle) {
            case self::EVENT_BEFORE:
                $guid = $this->_generateGuid();
                $json_data = $block->getData();
                
                if ($parent = $block->getParentBlock()) {
                    $json_data['parent'] = $parent->getData();
                }
                
                echo "<script type='text/javascript'>";
                echo "<!--\n";
                echo "if (typeof(diyhint) == 'undefined') { diyhint = {}; }; diyhint['$guid'] = " . json_encode($json_data) . ";";
                echo "\n-->";
                echo "</script>";
                echo "<div class='diy-hint' rel='$guid'>";
                // echo "<div class='shade'></div>";
                break;
            case self::EVENT_AFTER:
                echo "</div>";
                break;
        }
    }

    /**
     * @see http://phpgoogle.blogspot.com/2007/08/four-ways-to-generate-unique-id-by-php.html
     */
    protected function _generateGuid() {
        $s = strtoupper(md5(uniqid(rand(),true))); 
        $guidText = 
            substr($s,0,8) . '_' . 
            substr($s,8,4) . '_' . 
            substr($s,12,4). '_' . 
            substr($s,16,4). '_' . 
            substr($s,20); 
        return $guidText;
    }
}