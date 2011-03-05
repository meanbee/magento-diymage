<?php
// {{license}}
interface Meanbee_Diy_Model_Source_Interface {
    /**
     * Return the options for a dropdown in the format:
     *
     *     array (
     *         value_1 => label_1,
     *         value_2 => label_2 
     *     )
     *
     * @return array
     * @author Nicholas Jones
     */
    public function asArray();
}