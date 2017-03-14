<?php

class Codigo5_BoletoSimples_Helper_Attributes extends Codigo5_BoletoSimples_Helper_Data
{
    public function getAttribute(Varien_Object $object, $attributeCode, $default = null, $storeId = null, callable $handler = null)
    {
        $attribute = $this->getConfig("{$attributeCode}_attribute_code", $default, $storeId);

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
            null,
            $storeId
        );
    }

    public function getAddressLine(Mage_Sales_Model_Order_Address $address, $attributeCode, $storeId = null)
    {
        return $this->getAttribute(
            $address,
            $attributeCode,
            null,
            $storeId,
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
            null,
            $storeId
        );
    }

    public function getAddressNumber(Mage_Sales_Model_Order_Address $address, $storeId = null)
    {
        return $this->getAddressLine(
            $address,
            'address_number',
            null,
            $storeId
        );
    }

    public function getAddressComplement(Mage_Sales_Model_Order_Address $address, $storeId = null)
    {
        return $this->getAddressLine(
            $address,
            'address_complement',
            null,
            $storeId
        );
    }

    public function getAddressNeighborhood(Mage_Sales_Model_Order_Address $address, $storeId = null)
    {
        return $this->getAddressLine(
            $address,
            'address_neighborhood',
            null,
            $storeId
        );
    }
}
