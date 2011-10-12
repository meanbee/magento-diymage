<?php
class Meanbee_Diy_Model_Resource_Data_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
    protected function _construct() {
        $this->_init('diy/data', 'id');
    }
}