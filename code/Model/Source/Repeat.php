<?php
// {{license}}
class Meanbee_Diy_Model_Source_Repeat implements Meanbee_Diy_Model_Source_Interface {
    public function asArray() {
        $result = array(
            "repeat" => "Horizontally and vertically",
            "repeat-x" => "Horizontally",
            "repeat-y" => "Vertically",
            "no-repeat" => "Do not repeat",
            "inherit" => "Inherit"
        );
        
        return $result;
    }
}