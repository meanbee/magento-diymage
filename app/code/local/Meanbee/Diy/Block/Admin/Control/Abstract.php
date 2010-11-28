<?php
class Meanbee_Diy_Block_Admin_Control_Abstract extends Meanbee_Diy_Block_Admin_Abstract {
    public function getFieldName() {
        $id = $this->getUniqueId();
        return "diy[$id]";
    }
    
    public function getFieldId() {
        return $this->getName();
    }
    
    public function getLabel() {
        return $this->getControl()->getLabel();
    }
    
    public function getName() {
        return $this->getControl()->getName();
    }
    
    public function getHelpText() {
        return $this->getControl()->getHelp();
    }
    
    public function getValue() {
        return $this->getControl()->getValue();
    }
    
    public function getUniqueId() {
        return $this->getControl()->getId();
    }
    
    /**
     * Get the default value, as specified in the diy.xml
     *
     * @TODO: Use a variable store_id
     *
     * @return string
     * @author Nicholas Jones
     */
    public function getDefaultValue() {
        $xml = Mage::getModel("diy/xml")->getAttributes();
        
        $name = $this->getName();
        $group = Mage::registry('diy_current_template');
        
        if ($xml[$group]) {
            if ($xml[$group][$name]) {
                return $xml[$group][$name]["value"];
            }
        }
        
        return false;
    }
}