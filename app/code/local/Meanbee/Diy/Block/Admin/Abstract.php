<?php
// {{license}}
abstract class Meanbee_Diy_Block_Admin_Abstract extends Meanbee_Diy_Block_Abstract {
    public function getFormKey() {
        return Mage::getSingleton('core/session')->getFormKey();
    }
    
    protected function _getUrlModelClass() {
        return 'adminhtml/url';
    }
}