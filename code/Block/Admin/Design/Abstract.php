<?php
// {{license}}
abstract class Meanbee_Diy_Block_Admin_Design_Abstract extends Meanbee_Diy_Block_Admin_Abstract {
    protected $_title = "No title set";

    abstract protected function getDataGroup();

    public function _beforeToHtml() {
        parent::_beforeToHtml();

        Mage::register('diy_current_template', $this->getDataGroup());

        $this->createBlocks();

        return $this;
    }

    /**
     * @return void
     * @author Nicholas Jones
     */
    protected function getDataCollection($sub_group = false) {
        $data = Mage::getModel('diy/data');

        $collection = $data->getCollection()
                            ->addFieldToSelect('*')
                            ->addFieldToFilter('data_group', $this->getDataGroup())
                            ->addFieldToFilter('store_id', $this->getStoreId())
                            ->setOrder('sub_group')
                            ->setOrder('sort_order', 'asc');
                            
        if ($sub_group) {
            $collection->addFieldToFilter('sub_group', $sub_group);
        }
                            
        return $collection;
    }
    
    /**
     * Get a list of the 'sub groups' used within this page
     */
    protected function getGroupList() {
        $data = Mage::getModel('diy/data');

        $collection = $data->getCollection()
                            ->addFieldToSelect('sub_group')
                            ->addFieldToFilter('data_group', $this->getDataGroup())
                            ->addFieldToFilter('store_id', $this->getStoreId())
                            ->distinct(true);
                            
        return $collection;
    }
    
    protected function getGroupLabel($sub_group) {
        $groups = Mage::getSingleton('diy/xml')->getGroups();
        
        if (isset($groups[$this->getDataGroup()]) && isset($groups[$this->getDataGroup()][$sub_group])) {
            $sub_group_data = $groups[$this->getDataGroup()][$sub_group];
            
            return $sub_group_data['label'];
        }
        
        return 'Additional Settings';
    }

    /**
     * Return the currently active store.  If none set, return 1.
     *
     * @return int
     * @author Nicholas Jones
     */
    public function getStoreId() {
        return (Mage::getSingleton('diy/session')->getActiveStoreId()) ? Mage::getSingleton('diy/session')->getActiveStoreId() : 1;
    }

    /**
     * Make all of the controls for this area appear in the $this->getChildHtml() call
     * within the template file.
     *
     * If there are no blocks specified in any diy.xml, then output the blank block which,
     * incidentally, is not blank.
     *
     * @return void
     * @author Nicholas Jones
     */
    protected function createBlocks() {

        foreach ($this->getGroupList() as $group_data) {
            $container = $this->getLayout()->createBlock('diy/admin_design_util_container');

            $group = $group_data->getSubGroup();
            
            $container->setData('name', $this->getGroupLabel($group));
            
            if (count($this->getDataCollection($group)) > 0) {
                foreach ($this->getDataCollection($group) as $data) {
                    $block = $this->getLayout()->createBlock(
                        $data['input_control'],
                        'control_' . $data['name'],
                        array("control" => $data)
                    );
                    
                    $container->append($block);
                }
            } else {
                $container->append(
                    $this->getLayout()->createBlock(
                        'diy/admin_control_blank',
                        'control_empty_' . rand(0,9999),
                        array("template" => "diy/controls/blank.phtml")
                    )
                );
            }
            
            $this->append($container);
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

    public function getTitle() {
        return $this->_title;
    }
}
