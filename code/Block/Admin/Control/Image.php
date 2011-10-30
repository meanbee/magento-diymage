<?php
// {{license}}
class Meanbee_Diy_Block_Admin_Control_Image extends Meanbee_Diy_Block_Admin_Control_Dropdown {
    public function __construct() {
        parent::__construct();
        $this->setTemplate('diy/controls/image.phtml');
    }

}