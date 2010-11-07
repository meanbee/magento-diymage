<?php
class Meanbee_Diy_Model_Observer {
    public function layoutUpdates($observer) {
        $handle = Mage::getModel('diy/observer_layout');
        $handle->observe($observer);
    }
}