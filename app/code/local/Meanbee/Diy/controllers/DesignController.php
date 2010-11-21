<?php
class Meanbee_Diy_DesignController extends Mage_Adminhtml_Controller_Action {
    
    // public function _construct() {
    //     parent::_construct();
    //     
    //     $settings = Mage::getSingleton('diy/settings');
    //     
    //     if (!$settings->isEnabled()) {
    //         Mage::getSingleton('adminhtml/session')->addNotice(
    //             Mage::helper('diy')->__('DIY Mage is not currently enabled in your Magento configuration.  No changes will appear on the front end until the module is enabled.')
    //         );
    //     }
    // }
    
    public function preDispatch() {
        parent::preDispatch();
        
        $settings = Mage::getSingleton('diy/settings');
        
        if (!$settings->isEnabled()) {
            Mage::getSingleton('adminhtml/session')->addNotice(
                Mage::helper('diy')->__('DIY Mage is not currently enabled in your Magento configuration.  No changes will appear on the front end until the module is enabled.')
            );
        }
        
        return $this;
    }
    
    public function indexAction() {
        $this->__render();
    }
    
    public function listingAction() {
        $this->__render();
    }
    
    public function productAction() {
        $this->__render();
    }
    
    public function checkoutAction() {
        $this->__render();
    }
    
    public function cartAction() {
        $this->__render();
    }
    
    public function contactsAction() {
        $this->__render();
    }
    
    public function saveAction() {
        if ($data = $this->getRequest()->getPost("diy")) {
            $return_url = $this->getRequest()->getPost("return_url");
            $publish = (bool) $this->getRequest()->getPost("publish");
            
            foreach ($data as $id => $value) {
                if (!is_integer($id)) {
                    throw new Exception("I was expecting an integer there");
                }
                
                $data = Mage::getModel('diy/data')->load($id);
                $data->setValue($value);
                $data->save();
            }
            
            if ($publish) {
                try {
                    Mage::getSingleton('diy/stylesheet')->publish();
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('diy')->__('Your design changes have been saved and published successfully'));
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('diy')->__('There was an error publishing your changes, but your data has been saved (' . $e->getMessage() . ')'));                    
                }
            } else {
                Mage::getSingleton('adminhtml/session')->addNotice(Mage::helper('diy')->__('Your design changes have been saved successfully, but have not yet been published so may not yet be live'));
            }
        }
        
        if ($return_url) {
            $this->getResponse()->setRedirect(
                $this->getRequest()->getPost("return_url")
            );
        } else {
            $this->getResponse()->setRedirect(
                $this->getUrl('*/*')
            );
        }
    }
    
    private function __render() {
        $this->loadLayout()->_setActiveMenu('diy')->renderLayout();
    }
}