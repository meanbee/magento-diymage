<?php
class Meanbee_Diy_Model_Observer_Layout implements Meanbee_Diy_Model_Observer_Interface {
    public function observe($observer) {
        $action_obj = $observer->getAction();
        $layout = $observer->getLayout();
        $update = $layout->getUpdate();
        $request = $action_obj->getRequest();
        
        $module = $request->getModuleName();
        $action = $request->getActionName();
        $controller = $request->getControllerName();
        
        $full_identifier = "{$module}_{$controller}_{$action}";
        
        $identifiers = array(
            "{$module}",
            "{$module}_{$controller}",
            $full_identifier
        );
        
        $store_id = Mage::app()->getStore()->getStoreId();
        
        // This will become true if we match something in the next set of conditionals
        $layout_file = false;
        
        // Attempt to load the desired layout, working increasing precision with each step
        foreach ($identifiers as $identifier) {
            $update = Mage::helper('diy')->getValue($identifier, "layout");
            
            if ($update !== null) {
                $layout_file = $update;
            }
        }        
        
        if ($layout_file) {
            $this->_setTemplate($layout, $layout_file);
        }
        
        if (!Mage::helper('diy')->getValue("global", "show_categories")) {
            $this->_removeBlock($layout, "catalog.topnav");
        }
    }
    
    protected function _setTemplate($layout, $template) {
        $layout->getUpdate()->addUpdate(
            '<reference name="root">
                <action method="setTemplate"><template>' . $template . '</template></action>
            </reference>'
        );
    }
    
    protected function _addStylesheet($layout, $stylesheet) {
        $layout->getUpdate()->addUpdate(
            '<reference name="head">
                <action method="addItem"><type>skin_css</type><name>' . $stylesheet . '</name><params/><if /></action>
            </reference>'
        );
    }
    
    protected function _removeBlock($layout, $name) {
        $layout->getUpdate()->addUpdate(
            '<remove name="' . $name . '" />'
        );
    }
}