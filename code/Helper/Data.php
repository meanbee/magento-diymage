<?php
// {{license}}
class Meanbee_Diy_Helper_Data extends Mage_Core_Helper_Abstract {
    /**
     * @param string $group 
     * @param string $name 
     * @return void
     * @author Nicholas Jones
     */
    public function getValue($group, $name, $store_id) {
        $item = Mage::getModel('diy/data')->findByName($name, $group, $store_id);
        
        if ($item !== false) {
            return $item->getValue();
        } else {
            //throw new Exception("Unable to load value for $group/$name -- it does not exist");
        }
    }
}