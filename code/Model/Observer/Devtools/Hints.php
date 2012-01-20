<?php
// {{license}}
class Meanbee_Diy_Model_Observer_Devtools_Hints implements Meanbee_Diy_Model_Observer_Interface {
    
    const EVENT_BEFORE = 'core_block_abstract_to_html_before';
    const EVENT_AFTER  = 'core_block_abstract_to_html_after';

    protected $_log;
    
    public function __construct() {
        $this->_log = Mage::getModel('diy/log');
    }

    protected function _showHints() {
        return Mage::getSingleton("diy/config")->isBlockHintsEnabled();
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

        if (!($block instanceof Mage_Core_Block_Template) || !$this->_showHints()) {
            return;
        }

        $class_xml_type = $block->getType();
        $class_php_name = get_class($block);

        $template = $block->getTemplateFile();

        switch ($event_handle) {
            case self::EVENT_BEFORE:
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
<div class="diymage-hint">
    <div class="diymage-hint-template" onmouseover="this.style.zIndex='999'"onmouseout="this.style.zIndex='998'" title="{$template}">
        {$info_string}
    </div>
    <div class="diymage-hint-class" onmouseover="this.style.zIndex='999'" onmouseout="this.style.zIndex='998'"title="{$class_php_name}">
        {$class_php_name} ({$class_xml_type})
    </div>
HTML;
                break;
            case self::EVENT_AFTER:
                //
                echo "</div>";
                break;
        }
    }
}
