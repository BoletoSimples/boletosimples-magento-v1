<?php

class Codigo5_BoletoSimples_Model_Payment_Method_BoletoSimples extends Mage_Payment_Model_Method_Abstract
{
    const CODE = 'boletosimples';

    protected $_code = self::CODE;

    protected $_canUseInternal         = true;
    protected $_canUseCheckout         = true;
    protected $_canUseForMultishipping = true;
    protected $_isInitializeNeeded     = true;

    public function getOrderPlaceRedirectUrl()
    {
        return Mage::getUrl('codigo5_boletosimples/payment/request');
    }

    public function register(Mage_Sales_Model_Order $order)
    {
        // TODO After register the bank billet
        // set order state, status and comment with:
        //
        // $order->setStatus(
        //    Mage_Sales_Model_Order::STATE_PROCESSING,
        //    $paymentMethod->getConfigData('order_status'),
        //    $helper->__('Bank billet has been created'),
        //    false
        // );
        return array(
            'shorten_url' => 'https://bit.ly/cod5'
        );
    }
}
