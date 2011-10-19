<?php
// {{license}}
class Meanbee_Diy_DevtoolsController extends Mage_Core_Controller_Front_Action {
    public function cacheRefreshAction() {
        Mage::app()->getCache()->clean();
        $this->_redirectReferer();
    }
    
    public function indexRefreshAction() {
        $processes = Mage::getSingleton('index/indexer')->getProcessesCollection();
        
        foreach ($processes as $process) {
            $process->reindexEverything();
        }
        
        $this->_redirectReferer();
    }
    
    public function cacheDisableAction() {
        $cache_types = Mage::helper('core')->getCacheTypes();
        $enable = array();
        
        foreach ($cache_types as $type => $label) {
            $enable[$type] = 0;
        }
        
        Mage::app()->saveUseCache($enable);
        
        $this->_redirectReferer();
    }
}