<?php
class Meanbee_Diy_Model_Settings {
    public function isEnabled() {
        return Mage::getStoreConfig('diy/general/enabled');
    }
}