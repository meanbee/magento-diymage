<?php
// {{license}}

/* @var $installer Meanbee_Diy_Entity_Setup */
$installer = $this;

$installer->startSetup();

/**
 * Create the main DIY Mage table
 */
 
$table = $installer->getTable('diy/data');
$installer->run("

DROP TABLE `{$table}`;
CREATE TABLE `{$table}` (
    `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'DIY Mage Data ID',
    `name` text COMMENT 'Name',
    `label` text COMMENT 'Label',
    `data_group` text COMMENT 'Group',
    `input_control` text COMMENT 'Input Control',
    `help` text COMMENT 'Help',
    `value` text COMMENT 'Value',
    `source_model` text COMMENT 'Source Model',
    `sort_order` int(11) DEFAULT NULL COMMENT 'Sort Order',
    `store_id` int(11) DEFAULT NULL COMMENT 'Store ID',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='diy_data';

");

/**
 * Modify the CMS page table to include our builder
 */

$installer->getConnection()->addColumn($installer->getTable('cms_page'), 'diy_builder', 'text');

$installer->endSetup();

$installer->installEntities();

$installer->populateData();

Mage::getSingleton('adminhtml/session')->addSuccess(
    Mage::helper('diy')->__('DIY Mage: Database schema is now at 0.3.0 (Direct SQL Install)')
);
