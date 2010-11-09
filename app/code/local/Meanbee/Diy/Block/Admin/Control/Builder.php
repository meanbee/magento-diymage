<?php
class Meanbee_Diy_Block_Admin_Control_Builder extends Meanbee_Diy_Block_Admin_Control_Abstract {
    public function getLayoutReferenceJson($name) {
        $layout = Mage::getModel('diy/layout')->addHandle(Mage::registry('diy_current_template'));
        return Zend_Json::encode($layout->getReference($name));
    }
}