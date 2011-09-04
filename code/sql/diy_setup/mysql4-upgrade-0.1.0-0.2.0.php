<?php
// {{license}}

/* @var $installer Meanbee_Diy_Entity_Setup */
$installer = $this;

$conn = $installer->getConnection();
$table = $installer->getTable('cms_page');

$conn->addColumn($table, 'diy_builder', 'text');

$installer->endSetup();

Mage::getSingleton('adminhtml/session')->addSuccess(
    Mage::helper('diy')->__('DIY Mage: Database schema is now at 0.2.0 (Upgrade from 0.1.0)')
);
