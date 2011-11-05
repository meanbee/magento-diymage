<?php
// {{license}}
class Meanbee_Diy_Block_Devtools_Bar extends Meanbee_Diy_Block_Devtools_Abstract {
    public function _construct() {
        parent::_construct();
        $this->setTemplate('diy/devtools/bar.phtml');
    }
}
