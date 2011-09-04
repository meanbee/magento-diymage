<?php
// {{license}}
class Meanbee_Diy_Block_Admin_Control_Builder_Varien_Form_Element extends Varien_Data_Form_Element_Abstract {
    public function getElementHtml() {
        $block = new Meanbee_Diy_Block_Admin_Control_Builder();
        
        $block->setIsCMSPage(true);
        
        $control = new Varien_Object(array(
            "id"   => "diy_builder",
            "name" => "diy_builder"
        ));
        
        $block->setControl($control);
        
        return $block->toHtml();
    }
}