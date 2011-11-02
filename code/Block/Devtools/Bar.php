<?php
// {{license}}
class Meanbee_Diy_Block_Devtools_Bar extends Meanbee_Diy_Block_Abstract {
    public function __construct() {
        parent::__construct();

        $this->setTemplate('diy/devtools/bar.phtml');
    }
}
