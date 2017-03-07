<?php

class Codigo5_BoletoSimples_Helper_Data extends Mage_Core_Helper_Abstract
{
    const VERSION = '0.1.0';

    const LOGO_SKIN_PATH = 'adminhtml/default/default/codigo5/boletosimples/images/boletosimples.png';
    const REGISTER_URL   = 'https://boletosimples.com.br/users/sign_up';

    const AUTHOR_NAME           = 'Código5';
    const AUTHOR_URL            = 'https://www.codigo5.com.br';
    const AUTHOR_LOGO_SKIN_PATH = 'adminhtml/default/default/codigo5/boletosimples/images/codigo5.png';

    const XML_CONFIG_BASE_PATH = 'payment/boletosimples';

    public function getVersion()
    {
        return self::VERSION;
    }

    public function getLogoUrl()
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . self::LOGO_SKIN_PATH;
    }

    public function getRegisterUrl()
    {
        return self::REGISTER_URL;
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

    public function getConfig($node, $storeId = null, $default = null)
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

    public function isCpf($value)
    {
        return strlen($this->extractNumbers($value)) === 11;
    }

    public function extractNumbers($value)
    {
        return preg_replace('/\D/', '', $value);
    }

    public function getAttribute(Varien_Object $object, $attributeCode, $storeId = null, $default = null, callable $handler = null)
    {
        $attribute = $this->getConfig("{$attributeCode}_attribute_code", $storeId, $default);
        if ($attribute) {
            if ($handler) {
                return call_user_func_array($handler, array($object, $attribute));
            }
            return $object->getData($attribute);
        }
    }

    public function getCpfCnpj(Mage_Customer_Model_Customer $customer, $storeId = null)
    {
        return $this->getAttribute(
            $customer,
            'cpf_cnpj',
            $storeId
        );
    }

    public function getAddressLine(Mage_Sales_Model_Order_Address $address, $attributeCode, $storeId = null)
    {
        return $this->getAttribute(
            $address,
            $attributeCode,
            $storeId,
            null,
            function($address, $attribute) {
                return $address->getStreet($attribute);
            }
        );
    }

    public function getAddress(Mage_Sales_Model_Order_Address $address, $storeId = null)
    {
        return $this->getAddressLine(
            $address,
            'address',
            $storeId
        );
    }

    public function getAddressNumber(Mage_Sales_Model_Order_Address $address, $storeId = null)
    {
        return $this->getAddressLine(
            $address,
            'address_number',
            $storeId
        );
    }

    public function getAddressComplement(Mage_Sales_Model_Order_Address $address, $storeId = null)
    {
        return $this->getAddressLine(
            $address,
            'address_complement',
            $storeId
        );
    }

    public function getAddressNeighborhood(Mage_Sales_Model_Order_Address $address, $storeId = null)
    {
        return $this->getAddressLine(
            $address,
            'address_neighborhood',
            $storeId
        );
    }
}
