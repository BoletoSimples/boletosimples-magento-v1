<?php

class Codigo5_BoletoSimples_Model_Observer
{
    public function checkAuthConfig()
    {
        $_helper = Mage::helper('codigo5_boletosimples');

        if ($_helper->isPaymentMethodActive()) {
            $_helper->ensureLibrariesLoad();

            try {
                $user = BoletoSimples\Extra::userinfo();

                Mage::getSingleton('core/session')->addSuccess(
                    new Codigo5_BoletoSimples_MessageBuilder(
                        $_helper->__('Hello %s, we have successfully saved your credentials.', $user['full_name'])
                    )
                );
            } catch (Exception $e) {
                throw new Codigo5_BoletoSimples_Exception(
                    $_helper->__('Could not authenticate > "%s"', $e->getMessage())
                );
            }
        }
    }
}
