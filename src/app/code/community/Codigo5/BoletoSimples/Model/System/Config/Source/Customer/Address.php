<?php

class Codigo5_BoletoSimples_Model_System_Config_Source_Customer_Address extends Codigo5_BoletoSimples_Model_System_Config_Source_Abstract
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
            $_helper = Mage::helper('codigo5_boletosimples');
            $addressLinesCount = Mage::helper('customer/address')->getStreetLines();
            $this->_options = array_map(function($n) use ($_helper) {
                return $_helper->__('Line %s', $n);
            }, range(1, $addressLinesCount));
            $this->_options = $_helper->addBlankOption($this->_options);
        }

        return $this->_options;
    }
}
