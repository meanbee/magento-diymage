<?php
class Meanbee_Diy_Block_Admin_Switcher extends Meanbee_Diy_Block_Admin_Abstract {
    public function getStoreAsOptionArray() {
        $storeModel = Mage::getSingleton('adminhtml/system_store');
        $options = array();
        
        foreach ($storeModel->getWebsiteCollection() as $website) {
           foreach ($storeModel->getGroupCollection() as $group) {
               foreach ($storeModel->getStoreCollection() as $store) {
                   $options[$store->getId()] = $store->getName() . " (" . $website->getName() . ")";
               }
           }
        }

        return $options;
    }
}