<?php
// {{license}}
abstract class Meanbee_Diy_Block_Devtools_Abstract extends Meanbee_Diy_Block_Abstract {
    public function getArea() {
        return 'adminhtml';
    }

    public function getTemplateFile() {
        $params = array('_relative' => true, '_package' => 'default');
        $area = $this->getArea();
        if ($area) {
            $params['_area'] = $area;
        }
        $templateName = Mage::getDesign()->getTemplateFilename($this->getTemplate(), $params);
        return $templateName;
    }
}
