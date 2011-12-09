<?php
// {{license}}
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
    
    public function getStoreChangeUrl() {
        return $this->getUrl("*/*/*");
    }
    
    public function getActiveStore() {
        $session = Mage::getSingleton('diy/session')->getActiveStoreId();
        
        if (!$session) {
            $stores = $this->getStoreAsOptionArray();
            
            // Ensure we've not moved the array pointer
            reset($stores);
            
            // Return the store id of the first element
            $first_store = key($stores);
            
            Mage::getSingleton('diy/session')->setActiveStoreId($first_store);
        } else {
            return $session;
        }
    }
}