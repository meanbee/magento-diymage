<?php
class Meanbee_Diy_Block_Admin_Control_Font extends Meanbee_Diy_Block_Admin_Control_Dropdown {
    public function __construct() {
        parent::__construct();
        $this->setTemplate('diy/controls/font.phtml');
    }
    
    public function getOptions() {
        $source_model = Mage::getModel("diy/source_font");
        return $source_model->asArray();
    }
}