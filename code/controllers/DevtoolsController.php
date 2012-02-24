<?php
// {{license}}
class Meanbee_Diy_DevtoolsController extends Mage_Core_Controller_Front_Action {

    public function preDispatch() {
        // Ensure we're in the admin session namespace for checking the admin user..
        Mage::getSingleton('core/session', array('name' => 'adminhtml'))->start();

        $admin_logged_in = Mage::getSingleton('admin/session', array('name' => 'adminhtml'))->isLoggedIn();

        // ..get back to the original.
        Mage::getSingleton('core/session', array('name' => $this->_sessionNamespace))->start();

        if (!$admin_logged_in && $this->getRequest()->getActionName() != 'error') {
            $this->_forward('error');
        }

        return $this;
    }

    /**
     * Clear all the cache, then redirect to the referrer.
     */
    public function cacheRefreshAction() {
        Mage::app()->getCache()->clean();
        $this->_redirectReferer();
    }

    /**
     * Refresh all indexes, then redirect to the referrer.
     */
    public function indexRefreshAction() {
        $processes = Mage::getSingleton('index/indexer')->getProcessesCollection();
        
        foreach ($processes as $process) {
            $process->reindexEverything();
        }
        
        $this->_redirectReferer();
    }

    /**
     * Disable all caches, then redirect to the referrer.
     */
    public function cacheDisableAction() {
        $cache_types = Mage::helper('core')->getCacheTypes();
        $enable = array();
        
        foreach ($cache_types as $type => $label) {
            $enable[$type] = 0;
        }
        
        Mage::app()->saveUseCache($enable);
        
        $this->_redirectReferer();
    }

    /**
     * Toggle template hints, then redirect to the referrer.
     */
    public function templateToggleAction() {
        $config_paths = array(
            'dev/debug/template_hints',
            'dev/debug/template_hints_blocks'
        );
        
        foreach ($config_paths as $path) {
            $this->_setConfig($path, false);
        }

        $this->_toggleConfig('diy/general/developer_hints');

        $this->_redirectReferer();
    }

    /**
     * Toggle logging, then redirect to the referrer.
     */
    public function loggingToggleAction() {
        $this->_toggleConfig('dev/log/active');
        $this->_redirectReferer();
    }

    /**
     * Toggle symlink templates, then redirect to the referrer.
     */
    public function symlinkToggleAction() {
        $this->_toggleConfig('dev/template/allow_symlink');
        $this->_redirectReferer();
    }

    /**
     * @TODO Send the correct header information
     */
    public function errorAction() {
        echo $this->__("Only admins can perform developer tool modifications."); exit;
    }

    /**
     * Toggle true/flag Magento configuration options, based on a path.
     *
     * @param $path Configuration XML path
     */
    protected function _toggleConfig($path) {
        $old_value = Mage::getStoreConfigFlag($path);
        $new_value = !$old_value;
        
        Mage::getSingleton('core/config')->saveConfig($path, $new_value);
    }

    protected function _setConfig($path, $value) {
        Mage::getSingleton('core/config')->saveConfig($path, $value);
    }
}
