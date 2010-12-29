<?php
// {{license}}
class Meanbee_Diy_Model_Observer_Layout implements Meanbee_Diy_Model_Observer_Interface {
    protected $_removedBlocks = array();
    protected $_log;
    
    public function __construct() {
        $this->_log = Mage::getModel('diy/log');
    }
    
    /**
     * @TODO: Add event listeners for adding custom stylesheets
     *
     * @param string $observer 
     * @return void
     * @author Nicholas Jones
     */
    public function observe($observer) {
        
        if (!Mage::getSingleton('diy/config')->isEnabled()) {
            return;
        }
        
        $action_obj = $observer->getAction();
        $layout = $observer->getLayout();
        $update = $layout->getUpdate();
        $request = $action_obj->getRequest();
        
        $module = $request->getModuleName();
        $action = $request->getActionName();
        $controller = $request->getControllerName();
        
        $design = Mage::getDesign();
        $area = $design->getArea();
        
        if ($area == "adminhtml") {
            $this->_checkLicenseValid();
            return;
        }
        
        $full_identifier = "{$module}_{$controller}_{$action}";
        
        $this->_log->debug("--- Observing layout for $full_identifier");
        
        $identifiers = array(
            "{$module}",
            "{$module}_{$controller}",
            $full_identifier
        );
        
        $store_id = Mage::app()->getStore()->getStoreId();
        
        $this->_sortBlocks($identifiers, $layout);
        $this->_removeBlocks($identifiers, $layout);
        $this->_addStaticBlocks($identifiers, $layout);
        $this->_modifyPageLayout($identifiers, $layout);
        
        if (!Mage::helper('diy')->getValue("global", "show_categories")) {
            $this->_removeBlock($layout, "catalog.topnav");
        }
        
        $this->_addStylesheet($layout, "diymage.css?" . time());
        
        // Apply our sort changes..
        //$update->load();
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
                    
                    if ($remove == null) {
                        $remove = array();
                    }
                    
                    $to_remove = array_merge($to_remove, $remove);
                }
            }
        }
        
        if (count($to_remove) > 0) {
            $to_remove = array_unique($to_remove);
            
            $this->_removedBlocks = $to_remove;
            
            foreach ($to_remove as $block_name) {
                array_push($this->_removedBlocks, $block_name);
                $this->_removeBlock($layout, $block_name);
            }
        }
    }
    
    /**
     * Sort the blocks in the references.
     *
     * @see http://stackoverflow.com/questions/4410206/change-order-of-blocks-via-local-xml-file-in-magento
     * @param string $identifiers 
     * @param string $layout 
     * @return void
     * @author Nicholas Jones
     */
    protected function _sortBlocks($identifiers, $layout) {
        $this->_log->debug("Sorting blocks")->indent();
        $simple_xml = $layout->getUpdate()->asSimpleXml();
        
        foreach ($identifiers as $identifier) {
            $this->_log->debug("Searching for identifier $identifier")->indent();
            $update_xml = Zend_Json::decode(Mage::helper('diy')->getValue($identifier, "builder"));
            
            if (count($update_xml) > 0) {
                // An array of xml snippets
                $modified_updates = array();
                $updates = $layout->getUpdate()->asArray();
                
                foreach ($update_xml as $group => $data) {
                    $this->_log->debug("Searching for group $group")->indent();
                    $blocks = Zend_Json::decode($data['sort_order']);
                    $block_found = array();
                    
                    foreach ($blocks as $key => $block_data) {
                        $xml = "
                            <reference name='$group'>
                                <action method='unsetChild'><alias>{$block_data['name']}</alias></action>
                                <action method='insert'><blockName>{$block_data['name']}</blockName><siblingName>{$block_data['after']}</siblingName><after>1</after></action>
                            </reference>
                        ";
                        
                        $layout->getUpdate()->addUpdate($xml);
                    }
                }
            }
        }
    } // function
    
    /**
     * Utility method to generate the XML to set the template
     *
     * @param Mage_Core_Model_Layout $layout 
     * @param string $template 
     * @return void
     * @author Nicholas Jones
     */
    protected function _setTemplate($layout, $template) {
        $this->_log->debug("Setting template to $template");
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
        $this->_log->debug("Adding stylesheet $stylesheet");
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
        $this->_log->debug("Removing block $name");
        $layout->getUpdate()->addUpdate(
            '<remove name="' . $name . '" />'
        );
    }
    
    /**
     * Utility function to generate the XML to add a block
     *
     * @param string $layout 
     * @param string $reference
     * @param string $name 
     * @param string $type 
     * @param string $after 
     * @param string $before 
     * @param string $template 
     * @param string $as 
     * @return void
     * @author Nicholas Jones
     */
    protected function _addBlock($layout, $reference, $name, $type, $after = null, $before = null, $template = null, $as = null) {
        $this->_log->debug("Adding block $name of type $type to reference $reference");
        $arguments = array(
            "after", "before", "template", "as"
        );
        
        $attributes = array(
            "name" => $name,
            "type" => $type
        );
        
        foreach ($arguments as $argument) {
            if ($$argument) {
                $attributes[$argument] = $$argument;
            }
        }
        
        $xml = "<block ";
        
        foreach ($attributes as $key => $value) {
            $xml .= $key . "='". $value."' ";
        }
        
        $xml .= "/>";
        
        $xml = "<reference name='" . $reference . "'>" . $xml . "</reference>";
        
        $layout->getUpdate()->addUpdate($xml);
    }
    
    /**
     * undocumented function
     *
     * @param string $layout 
     * @param string $block_id The id of the static block in the database
     * @param string $block_name The name we need to use in the before/after statements of other layout references
     * @return void
     * @author Nicholas Jones
     */
    protected function _addStaticBlock($layout, $reference, $block_id, $block_name, $after = null, $before = null) {
        $this->_log->debug("Adding static block $block_id as $block_name");
        $xml = "<reference name='$reference'>";
            $xml .= '<block type="cms/block" name="' . $block_name . '"';
            
            if ($after) {
                $xml .= " after='$after' ";
            }
            
            if ($before) {
                $xml .= " before='$before' ";
            }
            
            $xml .= '>';
                $xml .= '<action method="setBlockId"><block_id>' . $block_id . '</block_id></action>';
            $xml .= '</block>';
        $xml .= '</reference>';
        
        $layout->getUpdate()->addUpdate($xml);
    }
    
    protected $_compiledEarlyLayout = null;
    protected function _identifyBlockType($identifiers, $layout, $name) {
        if ($this->_compiledEarlyLayout == null) {
            $this->_compiledEarlyLayout = $layout->getUpdate()->load($identifiers)->asSimpleXml();
        }
        
        $xpath = "reference/block[@name='{$name}']";
        
        $result = $this->_compiledEarlyLayout->xpath($xpath);
        
        if (count($result) > 0) {
            return $result[0];
        }
    }
    
    public function _addStaticBlocks($identifiers, $layout) {
        foreach ($identifiers as $identifier) {
            $update = Zend_Json::decode(Mage::helper('diy')->getValue($identifier, "builder"));

            if (count($update) > 0) {
                foreach ($update as $group => $data) {
                    $blocks = Zend_Json::decode($data['sort_order']);
                    
                    foreach ($blocks as $block) {
                        if ($block['static']) {
                            $this->_addStaticBlock($layout, $group, $block['static'], $block['name'], $block['after'], $block['before']);
                        }
                    }
                }
            }
        }
    }
    
    // http://www.imarc.net/communique/148-xml_pretty_printer_in_php5
    private function __xmlpp($xml, $html_output=false) {
        $xml_obj = new SimpleXMLElement($xml);
        $level = 4;
        $indent = 0; // current indentation level
        $pretty = array();

        // get an array containing each XML element
        $xml = explode("\n", preg_replace('/>\s*</', ">\n<", $xml_obj->asXML()));

        // shift off opening XML tag if present
        if (count($xml) && preg_match('/^<\?\s*xml/', $xml[0])) {
          $pretty[] = array_shift($xml);
        }

        foreach ($xml as $el) {
          if (preg_match('/^<([\w])+[^>\/]*>$/U', $el)) {
              // opening tag, increase indent
              $pretty[] = str_repeat(' ', $indent) . $el;
              $indent += $level;
          } else {
            if (preg_match('/^<\/.+>$/', $el)) {            
              $indent -= $level;  // closing tag, decrease indent
            }
            if ($indent < 0) {
              $indent += $level;
            }
            $pretty[] = str_repeat(' ', $indent) . $el;
          }
        }   
        $xml = implode("\n", $pretty);   
        return ($html_output) ? htmlentities($xml) : $xml;
    }
    
    /**
     * Contact the license server to determine whether a license is valid or not.
     *
     * @return bool
     * @author Nicholas Jones
     */
    protected function _checkLicenseValid() {
        $cache = Mage::getSingleton('diy/cache');
        $config = Mage::getSingleton('diy/config');
        $client = new Varien_Http_Client($config->getPingUrl());
        
        if ($cache->getLicenseStatus()) {
            $this->_log->debug("License valid, cache hit");
            return true;
        }
        
        if (!$config->hasCompletedLicenseFields()) {
            Mage::getSingleton('adminhtml/session')->addNotice(
                Mage::helper('diy')->__('You have not entered your license details for DIY Mage.')
            );
            
            $this->_log->warn("License fields are not complete");
            
            return false;
        }
        
        $post_data = array(
            "date"          => date("c"),
            "locale"        => Mage::getStoreConfig('general/locale/code'),
            "base_url"      => Mage::getStoreConfig('web/unsecure/base_url'),
            "license_key"   => $config->getLicenseKey(),
            "license_email" => $config->getLicenseEmail()
        );
        
        $client->setParameterPost('payload', $post_data);
        
        $this->_log->debug("Contacting server for license status");
        try {
            $response = $client->request(Zend_Http_Client::POST);

            if ($response->isSuccessful()) {
                if ($response->getHeader('Content-type') == "application/json") {
                    $result = json_decode($response->getBody(), true); // Convert to assoc array 

                    if ($result['valid']) {
                        $this->_log->debug("License is valid, confirmed by server");
                        $cache->setLicenseStatus(true);
                        return true;
                    } else {
                        Mage::getSingleton('adminhtml/session')->addError(
                            Mage::helper('diy')->__('Your license settings for DIY Mage are currently not valid.  Please contact support@diymage.com as soon as possible to resolve this issue.')
                        );

                        $this->_log->warn("Using an invalid license");
                    }
                } else {
                    $this->_log->critical("Incorrect content-type from the license server");
                }
            } else {
                throw new Exception();
            }
        } catch (Exception $e) {
            $this->_log->alert("Unable to contact license server");
        }
        
        return false;
    }
}