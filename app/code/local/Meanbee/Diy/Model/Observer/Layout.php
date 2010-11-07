<?php
class Meanbee_Diy_Model_Observer_Layout implements Meanbee_Diy_Model_Observer_Interface {
    public function observe($observer) {
        $action_obj = $observer->getAction();
        $layout = $observer->getLayout();
        $update = $layout->getUpdate();
        $request = $action_obj->getRequest();
        
        $action = $request->getActionName();
        $controller = $request->getControllerName();
        
        if ($action == "view") {
            $this->_setTemplate($layout, '1column');
            $this->_addStylesheet($layout, "test.css");
        }
    }
    
    protected function _setTemplate($layout, $template) {
        $layout->getUpdate()->addUpdate(
            '<reference name="root">
                <action method="setTemplate"><template>page/' . $template . '.phtml</template></action>
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