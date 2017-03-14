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
        $helper = Mage::helper('codigo5_boletosimples');
        $helper->ensureLibrariesLoad();

        $builder = Mage::getModel('codigo5_boletosimples/order_builder')->build($order);
        $bank_billet = BoletoSimples\BankBillet::create($builder->getData());

        // TODO: Handle errors from BoletoSimples API in a better way
        if (!$bank_billet->isPersisted()) {
            $fieldsErrors = array_filter($bank_billet->response_errors);
            $fieldsErrors = array_map(function($errors, $key) {
                if (is_array($errors)) {
                    $errors = implode(', ', $errors);
                }
                return "'{$key}' {$errors}";
            }, $fieldsErrors, array_keys($fieldsErrors));

            throw new Codigo5_BoletoSimples_Exception(implode(' / ', $fieldsErrors));
        }

        $paymentMethod = $helper->getPaymentMethod($order->getStoreId());

        $order
            ->setBoletosimplesBankBilletId($bank_billet->id)
            ->setBoletosimplesBankBilletUrl($bank_billet->shorten_url)
            ->setState(
               Mage_Sales_Model_Order::STATE_PROCESSING,
               $paymentMethod->getConfigData('order_status'),
               $helper->__('Bank billet has been created'),
               false
            )
            ->save();
    }
}
