<?php
class Meanbee_Diy_Block_Admin_Control_Abstract extends Meanbee_Diy_Block_Admin_Abstract {
    public function getFieldName() {
        $id = $this->getControl()->getId();
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
}