<?php

abstract class Codigo5_BoletoSimples_Model_System_Config_Source_Abstract
{
    /**
     * Options getter
     *
     * @return array
     */
    abstract public function toOptionArray();

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
