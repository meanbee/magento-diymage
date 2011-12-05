<?php
// {{license}}
class Meanbee_Diy_Model_Source_Border implements Meanbee_Diy_Model_Source_Interface {
    public function asArray() {
        $result = array(
            "none" => "None",
            "dotted" => "Dotted",
            "dashed" => "Dashed",
            "solid" => "Solid"
        );
        
        return $result;
    }
}