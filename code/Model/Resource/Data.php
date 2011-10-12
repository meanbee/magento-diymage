<?php
class Meanbee_Diy_Model_Resource_Data extends Mage_Core_Model_Resource_Db_Abstract {
    protected function _construct() {
        $this->_init('diy/data', 'id');
    }
}