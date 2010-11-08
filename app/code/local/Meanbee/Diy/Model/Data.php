<?php
/**
 * A fat EAV model to store all of our custom theme attribute data.
 *
 * Defined attributes:
 *     - name
 *     - group
 *     - help
 *     - value
 *
 * @category Meanbee
 * @package Meanbee_Diy
 * @author Nicholas Jones
 */
class Meanbee_Diy_Model_Data extends Mage_Core_Model_Abstract {
    
    protected function _construct() {
        $this->_init('diy/data');
    }
    
    public function validate() {
        $errors = array();
        $helper = Mage::helper('diy');
        
        if (empty($errors) || $this->getShouldIgnoreValidation()) {
            return true;
        }
        
        return $errors;
    }
    
    public function findByName($name, $group, $store_id) {
        $collection = $this->getCollection();
        $collection->addAttributeToSelect('*')
                   ->addAttributeToFilter('name', $name)
                   ->addAttributeToFilter('store_id', $store_id)
                   ->addAttributeToFilter('group', $group);
        
        if (count($collection) == 1) {
            return $collection->getFirstItem();
        } else if (count($collection) > 1) {
            Mage::exception("Found more than one data item with the same name/store_id combintation");
        } else {
            return false;
        }
    }
}