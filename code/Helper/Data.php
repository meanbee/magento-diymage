<?php
// {{license}}
class Meanbee_Diy_Helper_Data extends Mage_Core_Helper_Abstract {
    /**
     * @param string $group 
     * @param string $name 
     * @return void
     * @author Nicholas Jones
     */
    public function getValue($group, $name, $store_id = NULL) {
        if (is_null($store_id)) {
            // Are we in the admin area?
            if (Mage::getDesign()->getArea() == Mage_Core_Model_App_Area::AREA_ADMINHTML) {
                $store_id =  Mage::getSingleton('diy/session')->getActiveStoreId();
            } else {
                $store_id = Mage::app()->getStore()->getId();
            }
        }

        $item = Mage::getModel('diy/data')->findByName($name, $group, $store_id);
        
        if ($item !== false) {
            return $item->getValue();
        } else {
            //throw new Exception("Unable to load value for $group/$name -- it does not exist");
        }
    }
}
