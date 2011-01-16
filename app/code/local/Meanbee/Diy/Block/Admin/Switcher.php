<?php
class Meanbee_Diy_Block_Admin_Switcher extends Meanbee_Diy_Block_Admin_Abstract {
    public function getStoreAsOptionArray() {
        $storeModel = Mage::getSingleton('adminhtml/system_store');
        $options = array();
        
        foreach ($storeModel->getWebsiteCollection() as $website) {
           foreach ($storeModel->getGroupCollection() as $store) {
               if ($store->getWebsiteId() != $website->getId()) { continue; }
               foreach ($storeModel->getStoreCollection() as $view) {
                   if ($view->getGroupId() != $store->getId()) { continue; }
                   $options[$view->getId()] = $view->getName() . " (" . $website->getName() . "/" . $store->getName() . ")";
               }
           }
        }

        return $options;
    }
}