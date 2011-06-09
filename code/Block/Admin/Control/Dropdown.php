<?php
// {{license}}
class Meanbee_Diy_Block_Admin_Control_Dropdown extends Meanbee_Diy_Block_Admin_Control_Abstract {
    public function __construct() {
        parent::__construct();
        $this->setTemplate('diy/controls/dropdown.phtml');
    }
    
    public function getOptions() {
        $source_model = Mage::getModel($this->getControl()->getSourceModel());
        return $source_model->asArray();
    }
}