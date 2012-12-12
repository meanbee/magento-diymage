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
        
        // Build our initial group list with a default sort value of 9000 to try and
        // get them to the bottom of the page
        $group_list = array();                
        foreach ($collection as $item) {
            $group_list[$item->getSubGroup()] = 9000;
        }
                            
        // List the group sort order from the XML file
        $sub_groups = $this->_getDataGroupSubGroupsFromXml();
        $sort_order = array();
        foreach ($sub_groups as $name => $data) {
            $sort_order = $data['sort_order'];
            if (isset($group_list[$name])) {
                $group_list[$name] = $sort_order;
            }
        }
    
        // Sort the array on values
        asort($group_list, SORT_NUMERIC);

        return array_keys($group_list);
    }
    
    protected function _getDataGroupSubGroupsFromXml() {
        $groups = Mage::getSingleton('diy/xml')->getGroups();
        
        if (isset($groups[$this->getDataGroup()])) {
            return $groups[$this->getDataGroup()];
        }

        return false;
    }
    
    protected function getGroupLabel($sub_group) {
        $groups = $this->_getDataGroupSubGroupsFromXml();
        
        if ($groups && isset($groups[$sub_group])) {
            $sub_group_data = $groups[$sub_group];
            
            return $sub_group_data['label'];
        }
        
        return 'Additional Settings';
    }

    protected function getGroupHelp($sub_group) {
        $groups = $this->_getDataGroupSubGroupsFromXml();
        
        if ($groups && isset($groups[$sub_group])) {
            $sub_group_data = $groups[$sub_group];
            
            return $sub_group_data['help'];
        }
        
        return false;
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

        foreach ($this->getGroupList() as $group) {
            $container = $this->getLayout()->createBlock('diy/admin_design_util_container');
            
            $container->setData('name', $this->getGroupLabel($group));
            $container->setData('id', $group);
            $container->setData('help', $this->getGroupHelp($group));
            
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

    /**
     * Confirm that the DIY Mage stock theme is running on this store.
     *
     * @return bool
     * @author Nicholas Jones
     */    
    public function isDiyThemeEnabledForStore() {
        return $this->getCurrentThemePackage()->getPackage() == Meanbee_Diy_Helper_Data::DESIGN_PACKAGE_NAME;
    }
    
    /**
     * Extract the package and theme from the configuration, based on the current store.
     *
     * @return Varien_Object
     * @author Nicholas Jones
     */
    public function getCurrentThemePackage() {
        $package = Mage::getModel('core/design_package');
        $store = Mage::getModel('core/store')->load($this->getStoreId());
        
        $package->setStore($store);
        

        $package_name = $package->getPackageName();
        $theme_name = $package->getTheme('default');
        
        return new Varien_Object(array(
            "package" => $package_name,
            "theme" => $theme_name,
            "package_theme" => join('/', array($package_name, $theme_name))
        ));
    }
}
