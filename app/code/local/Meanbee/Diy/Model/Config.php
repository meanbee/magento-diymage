<?php
class Meanbee_Diy_Model_Config {
    protected function getXml() {
        return Mage::getConfig()->loadModulesConfiguration('diy.xml');
    }
    
    public function getAttributes() {
        $attributes = $this->getXml()->getXpath('diy/attributes');
        
        if (count($attributes) == 1) {
            return $attributes[0]->asArray();
        } else {
            throw new Exception("The number of attributes xml tags exceeded one.. I wasn't expecting that!");
        }
    }
}