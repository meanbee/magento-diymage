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

        if (!($block instanceof Mage_Core_Block_Template)) {
            return;
        }

        $type = $block->getType();
        $template = $block->getTemplateFile();

        switch ($event_handle) {
            case self::EVENT_BEFORE:
                $guid = $this->_generateGuid();
                $json_data = $block->getData();
                
                if ($parent = $block->getParentBlock()) {
                    $json_data['parent'] = $parent->getData();
                }

                $node = $block->getNode();

                $fileName = $template;

                $attr_name = $node['name'];
                $attr_as = $node['as'];

                $info_string = sprintf(
                    "%s%s%s",
                    $fileName,
                    ($attr_name) ? ' / name: ' . $attr_name : '',
                    ($attr_as) ? ' / as: ' . $attr_as : ''
                );

                echo <<<HTML
<div style="position:relative; border:1px dotted red; margin:6px 2px; padding:18px 2px 2px 2px; zoom:1;">
<div style="position:absolute; left:0; top:0; padding:2px 5px; background:red; color:white; font:normal 11px Arial;
text-align:left !important; z-index:998;" onmouseover="this.style.zIndex='999'"
onmouseout="this.style.zIndex='998'" title="{$fileName}">{$info_string}</div>
HTML;

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
