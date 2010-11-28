<?php
// {{license}}
class Meanbee_Diy_Model_Stylesheet {
    public function publish() {
        $block = Mage::getBlockSingleton('diy/stylesheet');
        $this->_writeToFile($block->toHtml());
    }
    
    /**
     * @TODO: Write to a different stylesheet depending on the store id
     *
     * @param string $string 
     * @return void
     * @author Nicholas Jones
     */
    protected function _writeToFile($string) {
        $file = Mage::getBaseDir() . "/skin/frontend/base/default/diymage.css";
        
        $result = file_put_contents($file, $string, LOCK_EX);
        
        if ($result !== false) {
            return true;
        } else {
            throw new Exception("There was an unknown error writing to the file");
        }
    }
}