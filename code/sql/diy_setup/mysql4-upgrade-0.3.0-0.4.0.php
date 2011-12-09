<?php
// {{license}}

/* @var $installer Meanbee_Diy_Entity_Setup */
$installer = $this;

$installer->startSetup();

/**
 * Add the 'sub group' column to the diy data table
 */
 
$table = $installer->getTable('diy/data');
$installer->run("

ALTER TABLE `{$table}` ADD COLUMN `sub_group` text NULL default NULL COMMENT 'Data Sub Group'

");

$installer->endSetup();

$installer->installEntities();

$installer->populateData();

Mage::getSingleton('adminhtml/session')->addSuccess(
    Mage::helper('diy')->__('DIY Mage: Database schema is now at 0.4.0 (SQL Upgrade from 0.3.0)')
);
