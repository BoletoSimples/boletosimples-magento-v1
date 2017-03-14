<?php

class Codigo5_BoletoSimples_Helper_Data extends Mage_Core_Helper_Abstract
{
    const VERSION = '0.1.0';

    const XML_CONFIG_BASE_PATH = 'payment/boletosimples';

    public function getVersion()
    {
        return self::VERSION;
    }

    public function buildMessage($message)
    {
        return Mage::getModel('codigo5_boletosimples/messageBuilder', $message);
    }

    public function wrapException(Exception $exception)
    {
        if ($exception instanceof Codigo5_BoletoSimples_Exception) {
            return $exception;
        }

        return new Codigo5_BoletoSimples_Exception(
            $exception->getMessage(),
            $exception->getCode(),
            $exception
        );
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

    public function getConfig($node, $default = null, $storeId = null)
    {
        $value = Mage::getStoreConfig(self::XML_CONFIG_BASE_PATH . '/' . $node, $storeId);

        if (is_null($value) && $default) {
            if (is_callable($default)) {
                return call_user_func($default);
            }

            return $default;
        }

        return $value;
    }

    public function saveConfig($node, $value, $scope = 'default', $scopeId = 0)
    {
        return Mage::getConfig()->saveConfig(self::XML_CONFIG_BASE_PATH . '/' . $node, $value, $scope, $scopeId);
    }

    public function getPaymentMethod($storeId = null)
    {
        return Mage::getModel('codigo5_boletosimples/payment_method_boletoSimples')
            ->setId(Codigo5_BoletoSimples_Model_Payment_Method_BoletoSimples::CODE)
            ->setStore($storeId);
    }

    public function isPaymentMethodActive($storeId = null)
    {
        return (bool)(int)$this->getPaymentMethod($storeId)->getConfigData('active');
    }

    public function matchPaymentMethod(Mage_Sales_Model_Order $order)
    {
        return $order->getPayment()->getMethod() === Codigo5_BoletoSimples_Model_Payment_Method_BoletoSimples::CODE;
    }

    public function addBlankOption($options)
    {
        array_unshift($options, array(
            'label' => $this->__('Please select...'),
            'value' => null
        ));

        return $options;
    }

    public function extractNumbers($value)
    {
        return preg_replace('/\D/', '', $value);
    }
}
