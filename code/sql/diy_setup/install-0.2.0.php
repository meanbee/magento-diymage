<?php
/* @var $installer Meanbee_Diy_Entity_Setup */
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('diy/data'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'DIY Mage Data ID')
    ->addColumn('name',          Varien_Db_Ddl_Table::TYPE_TEXT, 256,  array(), 'Name')
    ->addColumn('label',         Varien_Db_Ddl_Table::TYPE_TEXT, 256,  array(), 'Label')
    ->addColumn('data_group',    Varien_Db_Ddl_Table::TYPE_TEXT, 256,  array(), 'Group')
    ->addColumn('input_control', Varien_Db_Ddl_Table::TYPE_TEXT, 256,  array(), 'Input Control')
    ->addColumn('help',          Varien_Db_Ddl_Table::TYPE_TEXT, null, array(), 'Help')
    ->addColumn('value',         Varien_Db_Ddl_Table::TYPE_TEXT, null, array(), 'Value')
    ->addColumn('source_model',  Varien_Db_Ddl_Table::TYPE_TEXT, 256,  array(), 'Source Model')
    ->addColumn('sort_order',    Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Sort Order')
    ->addColumn('store_id',      Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Store ID');

$installer->getConnection()->createTable($table);

$installer->populateData();

$installer->endSetup();

Mage::getSingleton('adminhtml/session')->addSuccess(
    Mage::helper('diy')->__('DIY Mage: Database schema is now at 0.2.0 (Direct Install)')
);
