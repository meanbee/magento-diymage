<?php
// {{license}}
class Meanbee_Diy_Model_Config {
    /**
     * @return bool
     */
    public function isEnabled() {
        return Mage::getStoreConfigFlag('diy/general/enabled');
    }

    /**
     * @return bool
     */
    public function isLoggingEnabled() {
        return $this->isEnabled() && Mage::getStoreConfigFlag('diy/general/log_enabled');
    }

    /**
     * @return bool
     */
    public function isDeveloperMode() {
        return $this->isEnabled() && Mage::getStoreConfigFlag('diy/general/developer_enabled');
    }

    /**
     * @return mixed
     */
    public function isDeveloperToolbarEnabled() {
        return Mage::getStoreConfigFlag('diy/general/developer_toolbar_enabled');
    }

    /**
     * @return string
     */
    public function getLicenseEmail() {
        return Mage::getStoreConfig('diy/license/email');
    }

    /**
     * @return bool
     */
    public function hasCompletedLicenseFields() {
        return $this->getLicenseEmail();
    }

    /**
     * @return string
     */
    public function getVersion() {
        return (string) Mage::getConfig()->getNode('modules/Meanbee_Diy/version');
    }

    /**
     * @return string
     */
    public function getPingUrl() {
        return "http://ping.diymage.com";
    }

    /**
     * @return string
     */
    public function getNotificationsUrl() {
        return "http://notifications.diymage.com";
    }

    /**
     * @return string
     */
    public function getLogName() {
        return "diymage.log";
    }
}
