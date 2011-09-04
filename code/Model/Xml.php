<?php
// {{license}}
class Meanbee_Diy_Model_Xml {
    protected $_log;
    
    public function __construct() {
        $this->_log = Mage::getSingleton('diy/log');
    }
    
    /**
     * Extract and merge the data from all the diy.xml files.
     *
     * @return array
     */
    protected function getXml() {
        return Mage::getConfig()->loadModulesConfiguration('diy.xml');
    }

    /**
     * @throws Exception
     * @return array
     */
    public function getAttributes() {
        $attributes = $this->getXml()->getXpath('diy/attributes');
        
        if (count($attributes) == 1) {
            return $attributes[0]->asArray();
        } else {
            throw new Exception("The number of attributes xml tags was not one.. I wasn't expecting that!");
        }
    }
    
    /**
     * Find all of the entries in xpath diy/block_namemap.
     * 
     * @return array
     */
    public function getBlockNamemap() {
        $result = array();
        $map = $this->getXml()->getXpath('diy/block_namemap');
        
        if (count($map) == 1) {
            $xml_data = $map[0]->asArray();
            
            if (count($xml_data) > 0) {
                foreach ($xml_data as $entry) {
                    if (isset($entry['id']) && isset($entry['name'])) {
                        $result[$entry['id']] = $entry['name'];
                    } else {
                        $this->_log->warn("Block name map: Found an 'entry' missing either an id or a name: " . json_encode($entry));
                    }
                }
            }
        }
        
        return $result;
    }
    
    /**
     * Insert all of the attributes into the database.  If we find that the attribute already exists then we
     * delete and create a new attribute with the previous data.
     *
     * @return void
     * @author Nicholas Jones
     */
    public function repopulateData() {
        $groups = $this->getAttributes();
        $stores = Mage::getModel('core/store')->getCollection();

        foreach ($stores as $store) {
            $store_id = $store['store_id'];
            
            foreach($groups as $group => $attributes) {
                
                foreach ($attributes as $name => $attribute) {
                    /* @var $attribute Mage_Core_Model_Config_Element */

                    $collection = Mage::getModel('diy/data')->getCollection();
                    $data = Mage::getModel('diy/data');
                    
                    if ($result = $data->findByName($name, $group, $store_id)) {
                        $result->delete();
                        $values["value"] = $result->getValue();
                    } else {
                        $values["value"]    = $attribute['value'];
                    }
                    
                    $values["name"]             = $name;
                    $values["group"]            = $group;
                    $values["store_id"]         = $store_id;
                    $values["label"]            = $attribute['label'];
                    $values["help"]             = $attribute['help'];
                    $values["input_control"]    = $attribute['input_control'];
                    $values["source_model"]     = $attribute['source_model'];
                    $values["sort_order"]       = ($attribute['sort_order']) ? $attribute['sort_order'] : 0;

                    $data->setData($values);
                    $data->save();
                }
            }
        }
    }
}
