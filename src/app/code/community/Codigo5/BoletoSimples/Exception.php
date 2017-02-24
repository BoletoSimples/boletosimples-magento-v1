<?php

class Codigo5_BoletoSimples_Exception extends Exception
{
    public function __construct ($message = '', $code = 0, $previous = null)
    {
        parent::__construct(Mage::getModel('codigo5_boletosimples/messageBuilder', $message), $code, $previous);
    }
}
