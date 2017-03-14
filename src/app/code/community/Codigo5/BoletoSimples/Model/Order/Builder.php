<?php

class Codigo5_BoletoSimples_Model_Order_Builder extends Varien_Object
{
    protected $_helper;

    protected $_customer;

    protected $_address;

    public function build(Mage_Sales_Model_Order $order)
    {
        $this->reset();
        $this->addData(array(
            'amount' => $this->buildAmount($order),
            'expire_at' => $this->buildExpireAt($order),
            'description' => $this->buildDescription($order),
            'customer_person_name' => $this->buildCustomerPersonName($order),
            'customer_cnpj_cpf' => $this->buildCustomerCnpjCpf($order),
            'customer_email' => $this->buildCustomerEmail($order),
            'customer_phone_number' => $this->buildCustomerPhoneNumber($order),
            'customer_address' => $this->buildCustomerAddress($order),
            'customer_address_number' => $this->buildCustomerAddressNumber($order),
            'customer_address_complement' => $this->buildCustomerAddressComplement($order),
            'customer_neighborhood' => $this->buildCustomerNeighborhood($order),
            'customer_city' => $this->buildCustomerCity($order),
            'customer_state' => $this->buildCustomerState($order),
            'customer_zipcode' => $this->buildCustomerZipcode($order),
            'meta' => $this->buildMeta($order)
        ));
        return $this;
    }

    public function reset()
    {
        return $this->unsetData();
    }

    protected function getHelper()
    {
        if (is_null($this->_helper)) {
            $this->_helper = Mage::helper('codigo5_boletosimples/attributes');
        }
        return $this->_helper;
    }

    protected function getCustomer(Mage_Sales_Model_Order $order)
    {
        if (is_null($this->_customer)) {
            $this->_customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
        }
        return $this->_customer;
    }

    protected function getAddress(Mage_Sales_Model_Order $order)
    {
        if (is_null($this->_address)) {
            $this->_address = $order->getBillingAddress();
        }
        return $this->_address;
    }

    protected function buildAmount(Mage_Sales_Model_Order $order)
    {
        return $order->getGrandTotal();
    }

    protected function buildExpireAt(Mage_Sales_Model_Order $order)
    {
        return Mage::app()->getLocale()
            ->date()
            ->addDay($this->getExpiryDays($order))
            ->get('yyyy-MM-dd');
    }

    protected function buildDescription(Mage_Sales_Model_Order $order)
    {
        return $this->getHelper()->__('Order #%s', $order->getIncrementId());
    }

    protected function buildCustomerPersonName(Mage_Sales_Model_Order $order)
    {
        return $this->getCustomer($order)->getName();
    }

    protected function buildCustomerCnpjCpf(Mage_Sales_Model_Order $order)
    {
        return $this->getHelper()->getCpfCnpj($this->getCustomer($order), $order->getStoreId());
    }

    protected function buildCustomerEmail(Mage_Sales_Model_Order $order)
    {
        return $this->getCustomer($order)->getEmail();
    }

    protected function buildCustomerPhoneNumber(Mage_Sales_Model_Order $order)
    {
        return $this->getHelper()->extractNumbers($this->getAddress($order)->getTelephone());
    }

    protected function buildCustomerAddress(Mage_Sales_Model_Order $order)
    {
        return $this->getHelper()->getAddress($this->getAddress($order), $order->getStoreId());
    }

    protected function buildCustomerAddressNumber(Mage_Sales_Model_Order $order)
    {
        return $this->getHelper()->getAddressNumber($this->getAddress($order), $order->getStoreId());
    }

    protected function buildCustomerAddressComplement(Mage_Sales_Model_Order $order)
    {
        return $this->getHelper()->getAddressComplement($this->getAddress($order), $order->getStoreId());
    }

    protected function buildCustomerNeighborhood(Mage_Sales_Model_Order $order)
    {
        return $this->getHelper()->getAddressNeighborhood($this->getAddress($order), $order->getStoreId());
    }

    protected function buildCustomerCity(Mage_Sales_Model_Order $order)
    {
        return $this->getAddress($order)->getCity();
    }

    protected function buildCustomerState(Mage_Sales_Model_Order $order)
    {
        $regionId = $this->getAddress($order)->getRegionId();
        return Mage::getModel('directory/region')->load($regionId)->getCode();
    }

    protected function buildCustomerZipcode(Mage_Sales_Model_Order $order)
    {
        return $this->getAddress($order)->getPostcode();
    }

    protected function buildMeta(Mage_Sales_Model_Order $order)
    {
        return array(
            'order_id' => $order->getId()
        );
    }

    private function getExpiryDays(Mage_Sales_Model_Order $order)
    {
        return (int)$this->getHelper()->getConfig('expiry_days', $order->getStoreId(), 1);
    }
}
