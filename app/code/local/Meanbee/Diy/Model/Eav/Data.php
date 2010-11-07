<?php
class Meanbee_Diy_Model_Eav_Data extends Mage_Eav_Model_Entity_Abstract {
    public function _construct() {
        $resource = Mage::getSingleton('core/resource');
        $this->setType('diy_data');
        $this->setConnection(
            $resource->getConnection('diy_read'),
            $resource->getConnection('diy_write')
        );
    }
}