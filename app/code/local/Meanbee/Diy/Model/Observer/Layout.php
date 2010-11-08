<?php
class Meanbee_Diy_Model_Observer_Layout implements Meanbee_Diy_Model_Observer_Interface {
    public function observe($observer) {
        $action_obj = $observer->getAction();
        $layout = $observer->getLayout();
        $update = $layout->getUpdate();
        $request = $action_obj->getRequest();
        
        $action = $request->getActionName();
        $controller = $request->getControllerName();
        
        $store_id = Mage::app()->getStore()->getStoreId();
        
        $data = false; // This will become true if we match something in the next set of conditionals
        
        /* Per Section Template Changes */
        if ($controller == "category" && $action == "view") {
            $data = Mage::helper('diy')->getValue("listing", "layout");
        } else if ($controller == "product" && $action == "view") {
            $data = Mage::helper('diy')->getValue("product", "layout");
        } else if ($controller == "cart") {
            $data = Mage::helper('diy')->getValue("cart", "layout");
        } else if ($controller == "onepage") {
            $data = Mage::helper('diy')->getValue("checkout", "layout");
        }
        
        if ($data) {
            $this->_setTemplate($layout, $data->getValue());
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