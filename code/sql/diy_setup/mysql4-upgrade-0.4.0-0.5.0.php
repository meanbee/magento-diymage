<?php
// {{license}}

/* @var $installer Meanbee_Diy_Entity_Setup */
$installer = $this;

$installer->startSetup();

$blocks = array(
    'diymage_footer_column_1' => array(
        'heading' => 'Information',
        'links' => array(
            'about-magento-demo-store' => 'About Us',
            'contacts' => 'Contact Us'
        )
    ),
    'diymage_footer_column_2' => array(
        'heading' => 'More Links',
        'links' => array(
            'first' => 'Your First Link',
            'second' => 'Your Second Link'
        )
    ),
    'diymage_footer_column_3' => array(
        'heading' => 'Our Catalog',
        'links' => array(
            'catalog/seo_sitemap/product' => 'Product Sitemap',
            'catalog/seo_sitemap/category' => 'Category Sitemap',
            'catalogsearch/advanced' => 'Advanced Search'
        )
    )
);

/**
 * Insert some static blocks into the database
 */

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

foreach ($blocks as $ident => $block_data) {
    $html = "<h2>" . $block_data['heading'] . "</h2>\n";
    
    $html .= "<ul class='links'>\n";
    foreach ($block_data['links'] as $link => $name) {
        $html .= '    <li><a href="{{store url="' . $link . '"}}">' . $name . '</a></li>' . "\n";
    }
    $html .= "</ul>\n";

    try {
        Mage::getModel('cms/block')->setData(array(
            'title' => $block_data['heading'],
            'identifier' => $ident,
            'content' => $html,
            'is_active' => 1,
            'stores' => array(0)
        ))->save();
    } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError("DIY Mage: " . $e->getMessage());
    }
}

$installer->endSetup();

$installer->installEntities();

$installer->populateData();

Mage::getSingleton('adminhtml/session')->addSuccess(
    Mage::helper('diy')->__('DIY Mage: Database schema is now at 0.5.0 (SQL Upgrade from 0.4.0)')
);
