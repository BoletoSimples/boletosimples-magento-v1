<?php

class Codigo5_BoletoSimples_Model_MessageBuilder
{
    protected $message;
    protected $prefix;
    protected $suffix;
    protected $separator;

    public function __construct($message = '', $prefix = '[Boleto Simples]', $suffix = '', $separator = ' ')
    {
        $this->message = $message;
        $this->prefix = $prefix;
        $this->suffix = $suffix;
        $this->separator = $separator;
    }

    public function getMessage()
    {
        return implode($this->separator, array(
            $this->prefix,
            $this->message,
            $this->suffix
        ));
    }

    public function __toString()
    {
        return $this->getMessage();
    }
}
