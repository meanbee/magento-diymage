<?php
// {{license}}
class Meanbee_Diy_Model_Cache {
    const TAG = 'diymage';
    
    const KEY_LSTATUS  = 'lstatus';
    const KEY_NOTIFY   = 'notifications';
    const KEY_BLOCKMAP = 'block_name_map';
    const KEY_GROUPS   = 'xml_groups';
    
    public function isActive() {
        return Mage::app()->useCache(self::TAG);
    }
    
    public function save($key, $value, $lifetime = false) {
        Mage::app()->saveCache($value, $key, array(self::TAG), $lifetime);
        return $this;
    }
    
    public function load($key) {
        $result = Mage::app()->loadCache($key);
        return $result;
    }
}