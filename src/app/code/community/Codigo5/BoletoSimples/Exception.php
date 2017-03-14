<?php

class Codigo5_BoletoSimples_Exception extends Exception
{
    public function __construct ($message = '', $code = 0, $previous = null)
    {
        parent::__construct(Mage::helper('codigo5_boletosimples')->buildMessage($message), $code, $previous);
    }
}
