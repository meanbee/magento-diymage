<?php
class Meanbee_Diy_Model_Source_Layout implements Meanbee_Diy_Model_Source_Interface {
    public function asArray() {
        $layouts = Mage::getSingleton('page/config')->getPageLayouts();
        $result = array();
        
        if (count($layouts) > 0) {
            foreach ($layouts as $layout) {
                $result[$layout->getTemplate()] = $layout->getLabel();
            }
        }
        
        return $result;
    }
}