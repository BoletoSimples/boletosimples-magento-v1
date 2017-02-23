<?php

class Codigo5_BoletoSimples_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function addBlankOption($options)
    {
        array_unshift($options, array(
            'label' => $this->__('Please select...'),
            'value' => null
        ));
        return $options;
    }

    public function getPaymentMethod($store = null)
    {
        return Mage::getModel('codigo5_boletosimples/payment_method_boletoSimples')
            ->setId(Codigo5_BoletoSimples_Model_Payment_Method_BoletoSimples::CODE)
            ->setStore($store);
    }

    public function isPaymentMethodActive($store = null)
    {
        return (bool)(int)$this->getPaymentMethod()->getConfigData('active');
    }

    public function ensureLibrariesLoad()
    {
        if (require_once(Mage::getBaseDir('lib') . '/boletosimples/autoload.php')) {
            $this->setLibrariesConfig();
        }
    }

    public function setLibrariesConfig()
    {
        $paymentMethod = $this->getPaymentMethod();

        BoletoSimples::configure(array(
            'environment' => $paymentMethod->getConfigData('environment'),
            'access_token' => $paymentMethod->getConfigData('access_token')
        ));
    }
}
