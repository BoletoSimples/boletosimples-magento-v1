<?php

class Codigo5_BoletoSimples_Helper_Data extends Mage_Core_Helper_Abstract
{
    const VERSION = '0.1.0';

    const AUTHOR_NAME           = 'Código5';
    const AUTHOR_URL            = 'https://www.codigo5.com.br';
    const AUTHOR_LOGO_SKIN_PATH = 'adminhtml/default/default/codigo5/boletosimples/images/codigo5.png';

    public function getVersion()
    {
        return self::VERSION;
    }

    public function getAuthorName()
    {
        return self::AUTHOR_NAME;
    }

    public function getAuthorUrl()
    {
        return self::AUTHOR_URL;
    }

    public function getAuthorLogoUrl()
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . self::AUTHOR_LOGO_SKIN_PATH;
    }

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
