<?php
class Meanbee_Diy_Helper_Data extends Mage_Core_Helper_Abstract {
    /**
     * @TODO: Don't hardcode the store id
     *
     * @param string $group 
     * @param string $name 
     * @return void
     * @author Nicholas Jones
     */
    public function getValue($group, $name) {
        return Mage::getModel('diy/data')->findByName($name, Mage::getModel('diy/data')->identifyGroupId($group), 1)->getValue();
    }
}