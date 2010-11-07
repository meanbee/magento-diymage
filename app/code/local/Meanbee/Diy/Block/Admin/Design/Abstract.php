<?php
abstract class Meanbee_Diy_Block_Admin_Design_Abstract extends Meanbee_Diy_Block_Admin_Abstract {
    abstract protected function getDataGroup();
    
    public function _beforeToHtml() {
        parent::_beforeToHtml();
        $this->createBlocks();
        return $this;
    }
    
    protected function getDataCollection() {
        $data = Mage::getModel('diy/data');
        
        $collection = $data->getCollection()
                            ->addAttributeToSelect('*')
                            ->addAttributeToFilter('group', $this->getDataGroup())
                            ->addAttributeToFilter('store_id', 1);
        
        return $collection;
    }
    
    /**
     * Make all of the controls for this area appear in the $this->getChildHtml() call
     * within the template file.
     *
     * If there are no blocks specified in any diy.xml, then output the blank block which,
     * incedentaly, is not blank.
     *
     * @return void
     * @author Nicholas Jones
     */
    protected function createBlocks() {
        if (count($this->getDataCollection()) > 0) {
            foreach ($this->getDataCollection() as $data) {
                $block = $this->getLayout()->createBlock(
                    $data['input_control'],
                    'control_' . $data['name'],
                    array("control" => $data)
                );

                $this->append($block);
            }
        } else {
            $this->append(
                $this->getLayout()->createBlock(
                    'diy/admin_control_blank',
                    'control_empty_' . rand(0,9999),
                    array("template" => "diy/controls/blank.phtml")
                )
            );
        }
    }
    
    /**
     * Provide the URL, including the security key, that is used by the form to submit
     * and save the attributes.
     *
     * @return string
     * @author Nicholas Jones
     */
    public function getSaveUrl() {
        return $this->getUrl('*/*/save',
            array(
                '_current' => true
            )
        );
    }
}