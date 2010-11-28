<?php
// {{license}}
class Meanbee_Diy_Block_Stylesheet extends Meanbee_Diy_Block_Abstract {
    protected $_stylesheet_location;
    
    public function __construct() {
        parent::__construct();
        $this->setTemplate('diy/stylesheet.phtml');
    }
}