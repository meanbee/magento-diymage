<?php
class Meanbee_Diy_DesignController extends Mage_Adminhtml_Controller_Action {
    
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
    
    public function saveAction() {
        if ($data = $this->getRequest()->getPost("diy")) {
            $return_url = $this->getRequest()->getPost("return_url");
            
            foreach ($data as $id => $value) {
                if (!is_integer($id)) {
                    throw new Exception("I was expecting an integer there");
                }
                
                $data = Mage::getModel('diy/data')->load($id);
                $data->setValue($value);
                $data->save();
            }
            
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('diy')->__('Attributes updated'));
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