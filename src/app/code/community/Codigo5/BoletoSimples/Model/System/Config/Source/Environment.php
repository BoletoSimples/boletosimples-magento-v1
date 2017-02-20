<?php

class Codigo5_BoletoSimples_Model_System_Config_Source_Environment
{
    const ENVIRONMENT_STAGING = 'staging';
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
                    'label' => $_helper->__('Staging'),
                    'value' => self::ENVIRONMENT_STAGING
                ),
                array(
                    'label' => $_helper->__('Production'),
                    'value' => self::ENVIRONMENT_PRODUCTION
                )
            );
        }

        return $this->_options;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $array = array();

        foreach ($this->toOptionArray() as $option) {
            $array[$option['value']] = $option['label'];
        }

        return $array;
    }
}
