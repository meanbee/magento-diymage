<?php
// {{license}}
class Meanbee_Diy_Helper_Theme extends Mage_Core_Helper_Abstract {
    /**
     * Expand an input CSS class name from the controller, e.g. catalog-product-view, and output module level,
     * controller level and action specific CSS classes.
     *
     * @param  $class Typical getBodyClass() CSS class name, e.g. checkout-cart-index
     * @return string
     */
    public function expandBodyClass($class) {
        $delimiter = "-";
                
        // We only need to look at the first class if there's a list of them
        // This is the module/controller/action class
        $orig_classes = explode(" ", $class);

        $css_classes = array();
        
        // Add the other classes back on, we don't want to miss out on them
        for ( $i = 1; $i < count($orig_classes); $i++) {
            if (strlen($orig_classes[$i]) > 0) {
                $css_classes[] = $orig_classes[$i];
            } 
        }
        
        // No go through the parts
        $class = array_shift($css_classes);
        if (strstr($class, $delimiter) !== false) {
            $parts = explode($delimiter, $class);

            while (count($parts) > 0) {
                array_unshift($css_classes, join($delimiter, $parts));
                array_pop($parts);
            }
            
            return join(" ", array_unique($css_classes));
        } else {
            return $class;
        }
    }
}