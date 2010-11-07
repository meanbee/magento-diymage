<?php
class Meanbee_Diy_Entity_Setup extends Mage_Eav_Model_Entity_Setup {
    public function getDefaultEntities() {
        return array (
            'diy_data' => array(
                'entity_model'      => 'diy/data',
                'attribute_model'   => '',
                'table'             => 'diy/data',
                'attributes'        => array(
                    "name"          => $this->__addVarcharAttribute("Name"),
                    "label"         => $this->__addVarcharAttribute("Label"),
                    "group"         => $this->__addIntAttribute("Group"),
                    "input_control" => $this->__addVarcharAttribute("Input Control"),
                    "help"          => $this->__addTextAttribute("Help Text"),
                    "value"         => $this->__addTextAttribute("Value"),
                    "source_model"  => $this->__addVarcharAttribute("Source Model"),
                    
                    // Ideally, we'd use the store_id that forms part of the entity.. but Magento isn't playing nice..
                    "store_id"      => $this->__addIntAttribute("Store Id")
                ),
            )
        );
    }
    
    protected function __addVarcharAttribute($label, $default = "", $scope = 0) {
        return $this->__addAttribute("varchar", $label, $default, $scope);
    }
    
    protected function __addTextAttribute($label, $default = "", $scope = 0) {
        return $this->__addAttribute("text", $label, $default, $scope);
    }
    
    protected function __addIntAttribute($label, $default = "", $scope = 0) {
        return $this->__addAttribute("int", $label, $default, $scope);
    }
    
    protected function __addAttribute($type, $label, $default, $scope) {
        return array(
            'type'              => $type,
            'backend'           => '',
            'frontend'          => '',
            'label'             => $label,
            'input'             => 'text',
            'class'             => '',
            'source'            => '',                                                
            'global'            => 0, // Store = 0, Global = 1, Website = 2
            'visible'           => true,
            'required'          => true,
            'user_defined'      => true,
            'default'           => $default,
            'searchable'        => false,
            'filterable'        => false,
            'comparable'        => false,
            'visible_on_front'  => false,
            'unique'            => false
        );
    }
    
    /**
     * Insert all of the attributes into the database.  If we find that the attribute already exists then we
     * delete and create a new attribute with the previous data.
     *
     * @return void
     * @author Nicholas Jones
     */
    protected function populateData() {
        $groups = Mage::getModel("diy/config")->getAttributes();
        $stores = Mage::getModel('core/store')->getCollection();
        
        foreach ($stores as $store) {
            $store_id = $store['store_id'];
            
            foreach($groups as $group => $attributes) {
                $group_id = Mage::getModel('diy/data')->identifyGroupId($group);
                foreach ($attributes as $name => $attribute) {
                    $collection = Mage::getModel('diy/data')->getCollection();
                    $data = Mage::getModel('diy/data');
                    
                    if ($result = $data->findByName($name, $group_id, $store_id)) {
                        $result->delete();
                        $values["value"] = $result->getValue();
                    } else {
                        $values["value"]    = $attribute['value'];
                    }
                    
                    $values["name"]             = $name;
                    $values["group"]            = $group_id;
                    $values["store_id"]         = $store_id;
                    $values["label"]            = $attribute['label'];
                    $values["help"]             = $attribute['help'];
                    $values["input_control"]    = $attribute['input_control'];
                    $values["source_model"]     = $attribute['source_model'];

                    $data->setData($values);
                    $data->save();
                }
            }
        }
    }
}