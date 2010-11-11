<?php
class Meanbee_Diy_Model_Source_Font implements Meanbee_Diy_Model_Source_Interface {
    public function asArray() {
        $result = array(
            "georgia",
            "arial",
            "verdana",
            "tahoma"
        );
        
        return $result;
    }
}