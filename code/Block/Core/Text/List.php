<?php
class Meanbee_Diy_Block_Core_Text_List extends Mage_Core_Block_Text_List {
    /**
     * If <action method="setBlockOrder" /> was called on the block during the DIY Mage sorting process,
     * then we loop through those blocks, one by one, and call their toHtml() methods.
     *
     * If the setBlockOrder action wasn't called on this block, then resort to the default core/text_list
     * functionality.
     *
     * @return mixed|string
     */
    protected function _toHtml() {
        if ($this->getBlockOrder()) {
            $ordered_items = explode(',', $this->getBlockOrder());

            foreach ($ordered_items as $name) {
                $name = trim($name);
                $block = $this->getLayout()->getBlock($name);

                if ($block instanceof Mage_Core_Block_Abstract) {
                    $this->addText($block->toHtml());
                }
            }

            return $this->getText();
        }

        return parent::_toHtml();
    }
}