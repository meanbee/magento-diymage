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
        
        $this->_modifyPageLayout($identifiers, $layout);
        $this->_removeBlocks($identifiers, $layout);
        
        if (!Mage::helper('diy')->getValue("global", "show_categories")) {
            $this->_removeBlock($layout, "catalog.topnav");
        }
    }
    
    /**
     * Search through the $identifiers to find the layout we should apply.
     *
     * @param array $identifiers 
     * @param Mage_Core_Model_Layout $layout 
     * @return void
     * @author Nicholas Jones
     */
    protected function _modifyPageLayout($identifiers, $layout) {
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
    }
    
    /**
     * Find the blocks that we need to remove form the layout.
     *
     * @param array $identifiers 
     * @param Mage_Core_Model_Layout $layout
     * @return void
     * @author Nicholas Jones
     */
    protected function _removeBlocks($identifiers, $layout) {
        $to_remove = array();
        
        foreach ($identifiers as $identifier) {
            $update = Zend_Json::decode(Mage::helper('diy')->getValue($identifier, "builder"));
            
            if (count($update) > 0) {
                foreach ($update as $group => $data) {
                    $remove = Zend_Json::decode($data['remove']);
                    
                    $to_remove = array_merge($to_remove, $remove);
                }
            }
        }
        
        if (count($to_remove) > 0) {
            $to_remove = array_unique($to_remove);
            foreach ($to_remove as $block_name) {
                $this->_removeBlock($layout, $block_name);
            }
        }
    }
    
    /**
     * Utility method to generate the XML to set the template
     *
     * @param Mage_Core_Model_Layout $layout 
     * @param string $template 
     * @return void
     * @author Nicholas Jones
     */
    protected function _setTemplate($layout, $template) {
        $layout->getUpdate()->addUpdate(
            '<reference name="root">
                <action method="setTemplate"><template>' . $template . '</template></action>
            </reference>'
        );
    }
    
    /**
     * Utility method to generate the XML to add a stylesheet
     *
     * @param Mage_Core_Model_Layout $layout 
     * @param string $stylesheet 
     * @return void
     * @author Nicholas Jones
     */
    protected function _addStylesheet($layout, $stylesheet) {
        $layout->getUpdate()->addUpdate(
            '<reference name="head">
                <action method="addItem"><type>skin_css</type><name>' . $stylesheet . '</name><params/><if /></action>
            </reference>'
        );
    }
    
    /**
     * Utility function to generate the XML to remove blocks
     *
     * @param Mage_Core_Model_Layout $layout 
     * @param string $name 
     * @return void
     * @author Nicholas Jones
     */
    protected function _removeBlock($layout, $name) {
        $layout->getUpdate()->addUpdate(
            '<remove name="' . $name . '" />'
        );
    }
}