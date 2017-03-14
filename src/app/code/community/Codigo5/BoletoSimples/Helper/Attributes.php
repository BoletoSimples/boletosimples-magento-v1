<?php

class Codigo5_BoletoSimples_Helper_Attributes extends Codigo5_BoletoSimples_Helper_Data
{
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
