<?php

class Codigo5_BoletoSimples_Model_Observer
{
    public function checkAuthConfig()
    {
        $helper = Mage::helper('codigo5_boletosimples/webservice');

        if ($helper->isPaymentMethodActive()) {
            $helper->checkAuth();
            $helper->checkWebhooks();
        }
    }
}
