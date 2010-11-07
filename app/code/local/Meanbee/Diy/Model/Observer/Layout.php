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
            $data = Mage::getModel('diy/data')->findByName('layout', Meanbee_Diy_Model_Data::GROUP_LISTING, $store_id);
        } else if ($controller == "product" && $action == "view") {
            $data = Mage::getModel('diy/data')->findByName('layout', Meanbee_Diy_Model_Data::GROUP_PRODUCT, $store_id);
        } else if ($controller == "cart") {
            $data = Mage::getModel('diy/data')->findByName('layout', Meanbee_Diy_Model_Data::GROUP_CART, $store_id);
        } else if ($controller == "onepage") {
            $data = Mage::getModel('diy/data')->findByName('layout', Meanbee_Diy_Model_Data::GROUP_CHECKOUT, $store_id);
        }
        
        if ($data) {
            $this->_setTemplate($layout, $data->getValue());
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
}