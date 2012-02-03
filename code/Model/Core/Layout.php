<?php
// {{license}}
class Meanbee_Diy_Model_Core_Layout extends Mage_Core_Model_Layout {
    protected function _showHints() {
        return Mage::getSingleton("diy/config")->isBlockHintsEnabled();
    }

    protected function _generateBlock($node, $parent) {
        if (!$this->_showHints()) {
            return parent::_generateBlock($node, $parent);
        }


        if (!empty($node['class'])) {
            $className = (string)$node['class'];
        } else {
            $className = (string)$node['type'];
        }

        $blockName    = (string)$node['name'];
        $_profilerKey = 'BLOCK: ' . $blockName;
        Varien_Profiler::start($_profilerKey);

        $block = $this->addBlock($className, $blockName);

        /**
         * This is all we need to do, is pass the XML element information to the block that we're creating.
         */
        $block->setNode($node);

        if (!$block) {
            return $this;
        }

        if (!empty($node['parent'])) {
            $parentBlock = $this->getBlock((string)$node['parent']);
        } else {
            $parentName = $parent->getBlockName();
            if (!empty($parentName)) {
                $parentBlock = $this->getBlock($parentName);
            }
        }
        if (!empty($parentBlock)) {
            $alias = isset($node['as']) ? (string)$node['as'] : '';
            if (isset($node['before'])) {
                $sibling = (string)$node['before'];
                if ('-' === $sibling) {
                    $sibling = '';
                }
                $parentBlock->insert($block, $sibling, false, $alias);
            } elseif (isset($node['after'])) {
                $sibling = (string)$node['after'];
                if ('-' === $sibling) {
                    $sibling = '';
                }
                $parentBlock->insert($block, $sibling, true, $alias);
            } else {
                $parentBlock->append($block, $alias);
            }
        }
        if (!empty($node['template'])) {
            $block->setTemplate((string)$node['template']);
        }

        if (!empty($node['output'])) {
            $method = (string)$node['output'];
            $this->addOutputBlock($blockName, $method);
        }
        Varien_Profiler::stop($_profilerKey);

        return $this;
    }
}
