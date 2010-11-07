<?php
class Meanbee_Diy_Block_Admin_Control_Colour extends Meanbee_Diy_Block_Admin_Control_Abstract {
    public function __construct() {
        parent::__construct();
        $this->setTemplate('diy/controls/colour.phtml');
    }
}