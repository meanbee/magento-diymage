<?php
class Meanbee_Diy_Model_Log {
    protected $_fileName;
    protected $_indent = 0;
    
    public function __construct() {
        $this->_fileName = "diymage.log";
    }
    
    protected function _writeLog($message, $level) {
        if ($this->_indent > 0) {
            $indent_size = strlen($message) + ($this->_indent * 4);
            $message = str_pad($message, $indent_size, " ", STR_PAD_LEFT);
        } else if ($this->_indent < 0) {
            $this->_indent = 0;
        }
        
        Mage::log($message, $level, $this->_fileName, true);
    }
    
    public function debug($message) {
        $this->_writeLog($message, Zend_Log::DEBUG);
        return $this;
    }

    public function info($message) {
        $this->_writeLog($message, Zend_Log::INFO);
        return $this;
    }
    
    public function notice($message) {
        $this->_writeLog($message, Zend_Log::NOTICE);
        return $this;
    }
    
    public function warn($message) {
        $this->_writeLog($message, Zend_Log::WARN);
        return $this;
    }
    
    public function error($message) {
        $this->_writeLog($message, Zend_Log::ERR);
        return $this;
    }
    
    public function critical($message) {
        $this->_writeLog($message, Zend_Log::CRIT);
        return $this;
    }
    
    public function alert($message) {
        $this->_writeLog($message, Zend_Log::ALERT);
        return $this;
    }
    
    public function emergency($message) {
        $this->_writeLog($message, Zend_Log::EMERG);
        return $this;
    }
    
    public function indent() {
        $this->_indent++;
        return $this;
    }
    
    public function unindent() {
        $this->_indent--;
        return $this;
    }
}