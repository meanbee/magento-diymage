<?php
/* @var $installer Meanbee_Diy_Entity_Setup */
$installer = $this;

$installer->startSetup();

/**
 * Add the 'sub group' column to the diy data table
 */

$query = $installer->getConnection()->addColumn(
    $installer->getTable('diy/data'),
    'sub_group',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'unsigned'  => false,
        'nullable'  => true,
        'default'   => 'default',
        'comment'   => 'Data Sub Group'
    )
);

$installer->endSetup();

$installer->populateData();

Mage::getSingleton('adminhtml/session')->addSuccess(
    Mage::helper('diy')->__('DIY Mage: Database schema is now at 0.4.0 (DSL Upgrade from 0.3.0)')
);
