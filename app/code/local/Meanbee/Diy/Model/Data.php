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
    
    const GROUP_GLOBAL      = 1;
    const GROUP_PRODUCT     = 2;
    const GROUP_LISTING     = 3;
    const GROUP_CHECKOUT    = 4;
    const GROUP_CART        = 5;
    
    protected function _construct() {
        $this->_init('diy/data');
    }
    
    public function validate() {
        $errors = array();
        $helper = Mage::helper('diy');
        
        if (!in_array($this->getGroup(), $this->_allowedGroups())) {
            $errors[] = $helper->__('Invalid group type.  Please use one of these: %s.', implode(', ', $this->_accountTypes));
        }
        
        if (empty($errors) || $this->getShouldIgnoreValidation()) {
            return true;
        }
        
        return $errors;
    }
    
    public function findByName($name, $group_id, $store_id) {
        $collection = $this->getCollection();
        $collection->addAttributeToSelect('*')
                   ->addAttributeToFilter('name', $name)
                   ->addAttributeToFilter('store_id', $store_id)
                   ->addAttributeToFilter('group', $group_id);
        
        if (count($collection) == 1) {
            return $collection->getFirstItem();
        } else if (count($collection) > 1) {
            Mage::exception("Found more than one data item with the same name/store_id combintation");
        } else {
            return false;
        }
    }
    
    /**
     * Return an array of the values we consider valid
     *
     * @return array
     * @author Nicholas Jones
     */
    protected function _allowedGroups() {
        return array_values($this->_getGroupsMap());
    }
    
    protected function _getGroupsMap() {
        return array(
            "global"    => self::GROUP_GLOBAL,
            "product"   => self::GROUP_PRODUCT,
            "listing"   => self::GROUP_LISTING,
            "checkout"  => self::GROUP_CHECKOUT,
            "cart"      => self::GROUP_CART
        );
    }
    
    public function identifyGroupId($string) {
        $map = $this->_getGroupsMap();
        
        if (array_key_exists($string, $map)) {
            return $map[$string];
        } else {
            return false;
        }
    }
}