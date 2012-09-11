<?php
// {{license}}
class Meanbee_Diy_Model_Source_Font implements Meanbee_Diy_Model_Source_Interface {
    public function asArray() {
        $result = array(
            "",
            "arial",
            "courier",
            "georgia",
            "tahoma",
            "verdana"
        );
        
        return $result;
    }
}