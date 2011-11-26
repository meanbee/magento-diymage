<?php
// {{license}}
class Meanbee_Diy_DesignController extends Mage_Adminhtml_Controller_Action {
    
    public function preDispatch() {
        parent::preDispatch();
        
        $config = Mage::getSingleton('diy/config');
        
        if (!$config->isEnabled()) {
            Mage::getSingleton('adminhtml/session')->addNotice(
                Mage::helper('diy')->__('DIY Mage is not currently enabled in your Magento configuration.  No changes will appear on the front end until the module is enabled.')
            );
        }
        
        if ($config->isDeveloperMode()) {
            Mage::getSingleton('adminhtml/session')->addNotice(
                Mage::helper('diy')->__('Developer mode is currently enabled, meaning we\'re repopulating the attributes from the diy.xml files on each page load -- if you don\'t know what this means, <a href="' . $this->getUrl('adminhtml/system_config/edit/section/diy') . '">switch it off</a>.')
            );
            
            Mage::getSingleton('diy/xml')->repopulateData();
        }
        
        if ($store_id = $this->getRequest()->getParam('store_id')) {
            Mage::getSingleton('diy/session')->setActiveStoreId($store_id);
        } else {
            Mage::getSingleton('diy/session')->setActiveStoreId($this->__getDefaultStore());
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
    
    public function checkoutonepageAction() {
        $this->__render();
    }
    
    public function checkoutmultiAction() {
        $this->__render();
    }
    
    public function checkoutonepagesuccessAction() {
        $this->__render();
    }
    
    public function checkoutmultisuccessAction() {
        $this->__render();
    }
    
    public function cartAction() {
        $this->__render();
    }
    
    public function catalogsearchAction() {
        $this->__render();
    }
    
    public function advancedsearchAction() {
        $this->__render();
    }
    
    public function contactsAction() {
        $this->__render();
    }
    
    public function accountloginAction() {
        $this->__render();
    }
    
    public function accountcreateAction() {
        $this->__render();
    }
    
    public function accountdashboardAction() {
        $this->__render();
    }
    
    public function accountinfoAction() {
        $this->__render();
    }
    
    public function addressbookAction() {
        $this->__render();
    }
    
    public function addresseditAction() {
        $this->__render();
    }
    
    public function ordersAction() {
        $this->__render();
    }
    
    public function orderAction() {
        $this->__render();
    }
    
    public function billingagreementsAction() {
        $this->__render();
    }
    
    public function recurringprofilesAction() {
        $this->__render();
    }
    
    public function reviewsAction() {
        $this->__render();
    }
    
    public function tagsAction() {
        $this->__render();
    }
    
    public function wishlistAction() {
        $this->__render();
    }
    
    public function downloadableAction() {
        $this->__render();
    }
    
    public function newsletterAction() {
        $this->__render();
    }
    
    public function saveAction() {
        if ($data = $this->getRequest()->getPost("diy")) {
            $return_url = $this->getRequest()->getPost("return_url");
            $publish = (bool) $this->getRequest()->getPost("publish");
            
            // Handle saving settings
            foreach ($data as $id => $value) {
                if (!is_integer($id)) {
                    
                    // Check whether we're deleting an image
                    if (preg_match("/_delete$/", $id) && $value) {
                    
                        // Find the real id without "_delete"
                        $id = substr($id, 0, -7);
                        $value = "";
                    } else {
                    
                        // If we weren't then it's unrecognised input.
                        throw new Exception("I was expecting an integer there");
                    }
                }
                                
                $data = Mage::getModel('diy/data')->load($id);
                $data->setValue($value);
                $data->save();
            }
            
            // Upload images
            foreach ($_FILES as $id => $file) {
                if (isset($file['name']) && file_exists($file['tmp_name'])) {
                    try {
                        $uploader = new Mage_Core_Model_File_Uploader($id);
                        $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                        $uploader->setAllowRenameFiles(false);
                        $uploader->setFilesDispersion(false);
                        $uploader->setAllowCreateFolders(true);
                        $path = Mage::getBaseDir('skin') .DS. "frontend" .DS. "base" 
                            .DS. "default" .DS. "images" .DS. "diy" .DS;
                        $result = $uploader->save($path);
                        
                        $id = substr($id, 4);
                        $data = Mage::getModel('diy/data')->load($id);
                        $data->setValue($result['file']);
                        $data->save();
                    } catch (Exception $e) {
                        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    }
                }
            }
            
            if ($publish) {
                try {
                    Mage::getSingleton('diy/stylesheet')->publish(Mage::getSingleton('diy/session')->getActiveStoreId());
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
    
    private function __getDefaultStore() {
        $storeModel = Mage::getSingleton('adminhtml/system_store');
        $options = array();

        foreach ($storeModel->getWebsiteCollection() as $website) {
           foreach ($storeModel->getGroupCollection() as $store) {
               if ($store->getWebsiteId() != $website->getId()) { continue; }
               foreach ($storeModel->getStoreCollection() as $view) {
                   if ($view->getGroupId() != $store->getId()) { continue; }
                   return $view->getId();
               }
           }
        }
        
        return false;
    }
}