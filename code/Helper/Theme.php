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

        if (strstr($class, $delimiter) !== false) {
            $css_classes = array();
            $parts = explode($delimiter, $class);

            while (count($parts) > 0) {
                $css_classes[] = join($delimiter, $parts);
                array_pop($parts);
            }

            return join(" ", $css_classes);
        } else {
            return $class;
        }
    }
}