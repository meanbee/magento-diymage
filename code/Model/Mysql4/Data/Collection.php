<?php
class Meanbee_Diy_Model_Mysql4_Data_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
    protected function _construct() {
        $this->_init('diy/data', 'id');
    }
}