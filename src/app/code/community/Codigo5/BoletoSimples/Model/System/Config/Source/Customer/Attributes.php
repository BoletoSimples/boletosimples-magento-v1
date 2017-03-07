<?php

class Codigo5_BoletoSimples_Model_System_Config_Source_Customer_Attributes extends Codigo5_BoletoSimples_Model_System_Config_Source_Abstract
{
    protected $_options;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (is_null($this->_options)) {
            $this->_options = array();
            $_helper = Mage::helper('codigo5_boletosimples');
            $attributes = Mage::getModel('customer/entity_attribute_collection')
                ->addVisibleFilter();

            foreach ($attributes as $attribute) {
                $this->_options[] = array(
                    'label' => $attribute->getFrontendLabel(),
                    'value' => $attribute->getAttributeCode()
                );
            }

            $this->_options = $_helper->addBlankOption($this->_options);
        }

        return $this->_options;
    }
}
