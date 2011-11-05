<?php
// {{license}}
class Meanbee_Diy_Block_Devtools_Hints extends Meanbee_Diy_Block_Devtools_Abstract {
    public function _construct() {
        parent::_construct();
        $this->setTemplate('diy/devtools/hint_viewer.phtml');
    }
}
