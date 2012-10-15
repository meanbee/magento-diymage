<?php
// {{license}}

/* @var $installer Meanbee_Diy_Entity_Setup */
$installer = $this;

$installer->startSetup();

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

try {
    Mage::getModel('cms/block')->setData(array(
        'title' => "Footer Promo Text",
        'identifier' => "diymage_footer_promo_text",
        'content' => "This is some promo text!",
        'is_active' => 1,
        'stores' => array(0)
    ))->save();
} catch (Exception $e) {
    Mage::getSingleton('adminhtml/session')->addError("DIY Mage: " . $e->getMessage());
}

$installer->endSetup();

Mage::getSingleton('adminhtml/session')->addSuccess(
    Mage::helper('diy')->__('DIY Mage: Database schema is now at 1.0.2 (SQL Upgrade from 1.0.1)')
);