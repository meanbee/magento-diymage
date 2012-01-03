<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    tests
 * @package     selenium
 * @subpackage  runner
 * @author      Magento Core Team <core@magentocommerce.com>
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

define('DS', DIRECTORY_SEPARATOR);
define('PWD', realpath(dirname(__FILE__)));

define('SELENIUM_CONFIG_BASEDIR', PWD . DS . 'config');
define('SELENIUM_TESTS_BASEDIR', PWD);
define('SELENIUM_LIB_BASEDIR', realpath(PWD . DS . '../vendor/mtaf'));
define('SELENIUM_TESTS_SCREENSHOTDIR',
        realpath(SELENIUM_TESTS_BASEDIR . DS . 'tmp' . DS . 'screenshots'));

set_include_path(implode(PATH_SEPARATOR, array(
            SELENIUM_LIB_BASEDIR,
            realpath(SELENIUM_LIB_BASEDIR . DS . 'lib'),
            realpath(SELENIUM_TESTS_BASEDIR . DS . 'functional'), //To allow load tests helper files
            get_include_path(),
        )));

require_once 'Mage/Selenium/Autoloader.php';
Mage_Selenium_Autoloader::register();

require_once 'functions.php';

Mage_Selenium_TestConfiguration::initInstance();
