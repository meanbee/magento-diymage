<?php
// {{license}}
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