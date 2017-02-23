<?php

class Codigo5_BoletoSimples_Exception extends Exception
{
    public function __construct ($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(new Codigo5_BoletoSimples_MessageBuilder($message), $code, $previous);
    }
}
