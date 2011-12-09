<?php
// {{license}}
class Meanbee_Diy_Entity_Setup extends Mage_Eav_Model_Entity_Setup {
    public function populateData() {
        return Mage::getSingleton('diy/xml')->repopulateData();
    }
    
    public function getDefaultEntities() {}
}
