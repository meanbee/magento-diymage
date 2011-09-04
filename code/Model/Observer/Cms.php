<?php
// {{license}}
class Meanbee_Diy_Model_Observer_Cms implements Meanbee_Diy_Model_Observer_Interface {
    protected $_log;
    
    public function __construct() {
        $this->_log = Mage::getModel('diy/log');
    }
    
    public function observe(Varien_Event_Observer $observer) {
        $event = $observer->getEvent();
        $form = $event->getForm();
        
        $builderFieldset = $form->addFieldset('diy_fieldset', array(
            'legend' => Mage::helper('diy')->__('DIY Mage Builder'),
            'class'  => 'fieldset-wide'
        ));

        // Whoa! Look at the size of that bad boy!
        $builder = new Meanbee_Diy_Block_Admin_Control_Builder_Varien_Form_Element(array(
            'name'  => 'builder',
            'label' => ''
        ));
        
        $builder->setId('builder');
        
        $builderFieldset->addElement($builder);
    }
}
