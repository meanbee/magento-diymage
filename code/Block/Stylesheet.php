<?php
// {{license}}
class Meanbee_Diy_Block_Stylesheet extends Meanbee_Diy_Block_Abstract {
    protected $_stylesheet_location;
    
    public function __construct() {
        parent::__construct();
        $this->setTemplate('diy/stylesheet.phtml');
    }
    
    public function getStyleLine($group, $key, $css_property, $css_value_pattern = '%s') {
        $data_value = Mage::helper('diy')->getValue($group, $key);
        $css_value = sprintf($css_value_pattern, $data_value);

        if (!empty($data_value)) {
            return "$css_property: $css_value\n";
        } else {
            return "/* Ignored: $css_property: $css_value */ \n";
        }
    }
}