<?php

class Codigo5_BoletoSimples_Model_System_Config_Source_Environment extends Codigo5_BoletoSimples_Model_System_Config_Source_Abstract
{
    const ENVIRONMENT_SANDBOX = 'sandbox';
    const ENVIRONMENT_PRODUCTION = 'production';

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

            $this->_options = array(
                array(
                    'label' => $_helper->__('Sandbox'),
                    'value' => self::ENVIRONMENT_SANDBOX
                ),
                array(
                    'label' => $_helper->__('Production'),
                    'value' => self::ENVIRONMENT_PRODUCTION
                )
            );

            $this->_options = $_helper->addBlankOption($this->_options);
        }

        return $this->_options;
    }
}
