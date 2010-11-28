<?php
// {{license}}
class Meanbee_Diy_Model_Xml {
    protected function getXml() {
        return Mage::getConfig()->loadModulesConfiguration('diy.xml');
    }
    
    public function getAttributes() {
        $attributes = $this->getXml()->getXpath('diy/attributes');
        
        if (count($attributes) == 1) {
            return $attributes[0]->asArray();
        } else {
            throw new Exception("The number of attributes xml tags exceeded one.. I wasn't expecting that!");
        }
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