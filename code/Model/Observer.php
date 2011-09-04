<?php
// {{license}}
class Meanbee_Diy_Model_Observer {
    /**
     * The function listens out for controller_action_layout_generate_xml_before
     *
     * @see Mage_Core_Controller_Varien_Action
     * @param string $observer 
     * @return void
     * @author Nicholas Jones
     */
    public function layoutUpdates($observer) {
        $handle = Mage::getModel('diy/observer_layout');
        $handle->observe($observer);
    }
    
    /**
     * The function listens out for adminhtml_cms_page_edit_tab_design_prepare_form
     *
     * @see Mage_Core_Controller_Varien_Action
     * @param string $observer 
     * @return void
     * @author Nicholas Jones
     */
    public function adminCmsPageEdit($observer) {
        $handle = Mage::getModel('diy/observer_cms');
        $handle->observe($observer);
    }
}