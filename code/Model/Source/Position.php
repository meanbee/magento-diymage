<?php
// {{license}}
class Meanbee_Diy_Model_Source_Position implements Meanbee_Diy_Model_Source_Interface {
    public function asArray() {
        $result = array(
            "left top" => "Top Left",
            "center top" => "Top Centre", 
            "right top" => "Top Right",
            "left center" => "Centre Left",
            "center center" => "Centre Centre",
            "right center" => "Centre Right",
            "left bottom" => "Bottom Left",
            "center bottom" => "Bottom Centre",
            "right bottom" => "Bottom Right",
            "inherit" => "Inherit"
        );
        
        return $result;
    }
}