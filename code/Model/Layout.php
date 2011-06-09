<?php
// {{license}}
class Meanbee_Diy_Model_Layout {
    protected $_handles = array();
    
    public function __construct() {
        $package = Mage::getSingleton('core/design_package');
        
        $this->addHandle('STORE_' . Mage::app()->getStore()->getCode());
        $this->addHandle('THEME_' . $package->getArea() . '_' . $package->getPackageName() . '_' . $package->getTheme('layout'));
        $this->addHandle('default');
    }
    
    public function addHandle($handle) {
        $this->_handles[] = $handle;
        
        return $this;
    }
    
    public function getHandles() {
        return $this->_handles;
    }
    
    public function searchXpath($xpath) {
        $package = Mage::getSingleton('core/design_package');
        $layout = Mage::getModel('core/layout');
        $update = $layout->getUpdate();

        $previous_area = $package->getArea();
        $package->setArea(Mage_Core_Model_Design_Package::DEFAULT_AREA);
        
        $update->load($this->getHandles());
        
        $package->setArea($previous_area);
        
        return $update->asSimplexml()->xpath($xpath);
    }
    
    /**
     * @TODO: Make sure we're getting everything from the layout file
     * @TODO: Need to factor in the removes
     *
     * @param string $name 
     * @return void
     * @author Nicholas Jones
     */
    public function getReference($name) {
        $xpath = "reference[@name='{$name}']/block";
        $elements = $this->searchXpath($xpath);
        
        $result = array();
        
        foreach ($elements as $element) {
            $single = array(
                "name"      => $element->getAttribute("name"),
                "type"      => $element->getAttribute("type"),
                "as"        => $element->getAttribute("as"),
                "template"  => $element->getAttribute("template")
            );
            
            $result[] = $single;
        }
        
        return $result;
    }
}