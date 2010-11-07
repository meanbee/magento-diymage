<?php
class Meanbee_Diy_Model_Eav_Data_Collection extends Mage_Eav_Model_Entity_Collection_Abstract {
    protected function _construct() {
        $this->_init('diy/data');
    }
}