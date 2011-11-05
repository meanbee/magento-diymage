<?php
// {{license}}
class Meanbee_Diy_Model_Mysql4_Data extends Mage_Core_Model_Mysql4_Abstract {
    protected function _construct() {
        $this->_init('diy/data', 'id');
    }
}