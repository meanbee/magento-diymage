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
        
        $identifiers = array(
            "{$module}",
            "{$module}_{$controller}",
            "{$module}_{$controller}_{$action}"
        );
        
        $store_id = Mage::app()->getStore()->getStoreId();
        
        $layout_file = false; // This will become true if we match something in the next set of conditionals
        
        /*
        @TODO: Use this for displaying the layout, not the nested if-else
        foreach ($identifiers as $identifier) {
            $update = Mage::helper('diy')->getValue($identifier, "layout")
            
            if ($update !== false) {
                $layout_file = $update;
            }
        }
        */
        
        /* Per Section Template Changes */
        if ($controller == "category" && $action == "view") {
            $layout_file = Mage::helper('diy')->getValue("listing", "layout");
        } else if ($controller == "product" && $action == "view") {
            $layout_file = Mage::helper('diy')->getValue("product", "layout");
        } else if ($controller == "cart") {
            $layout_file = Mage::helper('diy')->getValue("cart", "layout");
        } else if ($controller == "onepage") {
            $layout_file = Mage::helper('diy')->getValue("checkout", "layout");
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