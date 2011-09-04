<?php
// {{license}}

/* @var $installer Meanbee_Diy_Entity_Setup */
$installer = $this;

$installer->addEntityType('diy_data', array(
    'entity_model'          => 'diy/data',
    'attribute_model'       => '',
    'table'                 => 'diy/data',
    'increment_model'       => '',
    'increment_per_store'   => '0'
));

$installer->createEntityTables(
    $this->getTable('diy/data')
);

$installer->installEntities();

$installer->populateData();

Mage::getSingleton('adminhtml/session')->addSuccess(
    Mage::helper('diy')->__('DIY Mage: Database schema is now at 0.1.0 (Direct Install)')
);
