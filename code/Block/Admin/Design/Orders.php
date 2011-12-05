<?php
// {{license}}
class Meanbee_Diy_Block_Admin_Design_Orders extends Meanbee_Diy_Block_Admin_Design_Abstract {
    protected $_title = "Customer Orders Design Settings";
    
    protected function getDataGroup() {
        return "sales_order_history";
    }
}