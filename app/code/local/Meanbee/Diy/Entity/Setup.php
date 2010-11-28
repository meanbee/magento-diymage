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
                    "group"         => $this->__addVarcharAttribute("Group"),
                    "input_control" => $this->__addVarcharAttribute("Input Control"),
                    "help"          => $this->__addTextAttribute("Help Text"),
                    "value"         => $this->__addTextAttribute("Value"),
                    "source_model"  => $this->__addVarcharAttribute("Source Model"),
                    "sort_order"    => $this->__addIntAttribute("Sort Order"),
                    
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
    
    protected function populateData() {
        return Mage::getSingleton('diy/xml')->repopulateData();
    }
}