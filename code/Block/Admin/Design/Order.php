<?php
// {{license}}
class Meanbee_Diy_Block_Admin_Design_Order extends Meanbee_Diy_Block_Admin_Design_Abstract {
    protected $_title = "Customer Order Design Settings";
    
    protected function getDataGroup() {
        return "sales_order_view";
    }
}