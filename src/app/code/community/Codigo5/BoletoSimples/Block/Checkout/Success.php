<?php

class Codigo5_BoletoSimples_Block_Checkout_Success extends Mage_Checkout_Block_Onepage_Success
{
    public function getPrintButtonBlock()
    {
        return $this->getLayout()
            ->createBlock('codigo5_boletosimples/print_button')
            ->setOrderId($this->getOrderId())
            ->setWindow(true);
    }
}
