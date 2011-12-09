<?php
// {{license}}
class Meanbee_Diy_Model_Cache {
    const TAG = 'diymage';

    /**
     * License status key.
     */
    const KEY_LSTATUS  = 'lstatus';

    /**
     * Notifications feed.
     */
    const KEY_NOTIFY   = 'notifications';

    /**
     * Block name map, loaded from XML.
     */
    const KEY_BLOCKMAP = 'block_name_map';

    /**
     * Field groups, loaded from XML.
     */
    const KEY_GROUPS   = 'xml_groups';

    /**
     * @return bool
     */
    public function isActive() {
        return Mage::app()->useCache(self::TAG);
    }

    /**
     * Save a value in the cache, and tag it as being related to DIY Mage.
     *
     * @param $key
     * @param $value
     * @param bool $lifetime
     * @return Meanbee_Diy_Model_Cache
     */
    public function save($key, $value, $lifetime = false) {
        Mage::app()->saveCache($value, $key, array(self::TAG), $lifetime);
        return $this;
    }

    /**
     * Load a value from cache.
     *
     * @param $key
     * @return mixed
     */
    public function load($key) {
        $result = Mage::app()->loadCache($key);
        return $result;
    }
}
