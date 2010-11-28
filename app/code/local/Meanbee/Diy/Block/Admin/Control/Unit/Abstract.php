<?php
// {{license}}
abstract class Meanbee_Diy_Block_Admin_Control_Unit_Abstract extends Meanbee_Diy_Block_Admin_Control_Abstract {
    public function __construct() {
        parent::__construct();
        $this->setTemplate('diy/controls/unit.phtml');
    }
    
    abstract public function getUnit();
}