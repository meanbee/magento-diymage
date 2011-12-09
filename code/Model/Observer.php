<?php
// {{license}}
class Meanbee_Diy_Model_Observer {
    /**
     * The function listens out for controller_action_layout_generate_xml_before
     *
     * @see Mage_Core_Controller_Varien_Action
     * @param Varien_Event_Observer $observer
     * @return void
     * @author Nicholas Jones
     */
    public function layoutUpdates(Varien_Event_Observer $observer) {
        $handle = Mage::getModel('diy/observer_layout');
        $handle->observe($observer);
    }
    
    /**
     * The function listens out for adminhtml_cms_page_edit_tab_design_prepare_form
     *
     * @see Mage_Core_Controller_Varien_Action
     * @param Varien_Event_Observer $observer
     * @return void
     * @author Nicholas Jones
     */
    public function adminCmsPageEdit(Varien_Event_Observer $observer) {
        $handle = Mage::getModel('diy/observer_cms');
        $handle->observe($observer);
    }
    
    /**
     * The function listens out for controller_action_layout_render_before
     *
     * @see Mage_Core_Controller_Varien_Action
     * @param Varien_Event_Observer $observer
     * @return void
     * @author Nicholas Jones
     */
    public function addDevTools(Varien_Event_Observer $observer) {
        $handle = Mage::getModel('diy/observer_devtools');
        $handle->observe($observer);
    }
    
    /**
     * The function listens out for core_block_abstract_to_html_before or core_block_abstract_to_html_after
     *
     * @param Varien_Event_Observer $observer
     * @return void
     * @author Nicholas Jones
     */
    public function addHint(Varien_Event_Observer $observer) {
        $handle = Mage::getModel('diy/observer_devtools_hints');
        $handle->observe($observer);
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function checkWritablePaths(Varien_Event_Observer $observer) {
        $handle = Mage::getModel('diy/observer_writable');
        $handle->observe($observer);
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function checkNotificationFeed(Varien_Event_Observer $observer) {
        $handle = Mage::getModel('diy/observer_feed');
        $handle->observe($observer);
    }
}
