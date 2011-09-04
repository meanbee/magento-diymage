<?php
// {{license}}

// @TODO Make this abstract and create the logger
interface Meanbee_Diy_Model_Observer_Interface {
    public function observe(Varien_Event_Observer $observer);
}