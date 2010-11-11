<?php
class Meanbee_Diy_Model_Observer_Layout implements Meanbee_Diy_Model_Observer_Interface {
    protected $_removedBlocks = array();
    
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
        $this->_sortBlocks($identifiers, $layout);
        $this->_removeBlocks($identifiers, $layout);
        
        if (!Mage::helper('diy')->getValue("global", "show_categories")) {
            $this->_removeBlock($layout, "catalog.topnav");
        }
        
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
     * @param string $identifiers 
     * @param string $layout 
     * @return void
     * @author Nicholas Jones
     */
    protected function _sortBlocks($identifiers, $layout) {
        $simple_xml = $layout->getUpdate()->asSimpleXml();
        
        foreach ($identifiers as $identifier) {
            $update_xml = Zend_Json::decode(Mage::helper('diy')->getValue($identifier, "builder"));
            
            if (count($update_xml) > 0) {
                // An array of xml snippets
                $modified_updates = array();
                $updates = $layout->getUpdate()->asArray();
                
                foreach ($update_xml as $group => $data) {
                    $blocks = Zend_Json::decode($data['sort_order']);
                    $block_found = array();
                    
                    foreach ($updates as $update_number => &$update) {
                        $lines = explode("\n", $update);
                        foreach ($lines as $line_number => &$line) {
                            $line = trim($line);
                            if (substr($line, 0, 6) == "<block") {
                                $parts = explode(" ", $line);
                                    
                                foreach ($blocks as $key => $block_data) {
                                    // We need to establish the type of the block
                                    $element = $this->_identifyBlockType($identifiers, $layout, $block_data['name']);
                                    
                                    if ($element === null) {
                                        throw new Exception("Could not identify block: " . $block_data['name']);
                                    }

                                    $type = $element->getAttribute('type');
                                    $before = $element->getAttribute('before');
                                    $template = $element->getAttribute('template');
                                    $as = $element->getAttribute('as');
                                    
                                    if (!isset($block_found[$block_data['name']])) {
                                        $block_found[$block_data['name']] = 0;
                                    }
                                    
                                    foreach ($parts as $part) {
                                        $subparts = explode("=", $part);

                                        if (count($subparts) != 2) {
                                            continue;
                                        }

                                        $key = $subparts[0];
                                        $value = str_replace(">", "", $subparts[1]);
                                        
                                        if ($key == "name" && $value == '"' . $block_data['name'] . '"') {
                                            $block_found[$block_data['name']] = 1;

                                            $after_string = 'after="' . $block_data['after'] . '"';
                                            $before_string = 'before="' . $block_data['before'] . '"';
                                            
                                            if (!$block_data['before']) {
                                                $before_string = '';
                                            }

                                            $pattern_b = '/before=["\'].*?["\']/i';
                                            $line = preg_replace($pattern_b, $before_string, $line, -1, $count_b);

                                            // If the line contains an after="", then we replace it..
                                            $count_a = 0;
                                            $pattern_a = '/after=["\'].*?["\']/i';
                                            $updated_line = preg_replace($pattern_a, $after_string, $line, -1, $count_a);

                                            if ($count_a == 0) {
                                                // .. otherwise, just add it in
                                                if (substr($line, strlen($line) - 2) == "/>") {
                                                    $updated_line = substr($line, 0, -2) . " " . $after_string . " />";
                                                } else {
                                                    $updated_line = substr($line, 0, -1) . " " . $after_string . ">";
                                                }
                                            }

                                            if ($count_b == 0) {
                                                if (substr($updated_line, strlen($updated_line) - 2) == "/>") {
                                                    $updated_line = substr($updated_line, 0, -2) . " " . $before_string . " />";
                                                } else {
                                                    $updated_line = substr($updated_line, 0, -1) . " " . $before_string . ">";
                                                }
                                            }
                                            
                                            $line = $updated_line;

                                            $modified_updates[] = $update_number; 
                                        }
                                    }
                                }                    
                            }
                        }
                        
                        $update = implode("\n", $lines);
                    } // foreach
                } // foreach
                
                foreach ($modified_updates as $idx) {
                    $updates[] = $updates[$idx];
                    unset($updates[$idx]); 
                }
                
                $layout->getUpdate()->resetUpdates();
                foreach ($updates as $update) {
                    $layout->getUpdate()->addUpdate($update);
                }
            } // if
        } // foreach
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
}