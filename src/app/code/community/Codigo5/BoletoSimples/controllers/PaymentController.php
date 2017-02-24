<?php

class Codigo5_BoletoSimples_PaymentController extends Mage_Core_Controller_Front_Action
{
    public function requestAction()
    {
        $checkout = Mage::getSingleton('checkout/session');
        $orderIncrementId = $checkout->getLastRealOrderId();

        if (!$orderIncrementId) {
            return $this->norouteAction();
        }

        $helper = Mage::helper('codigo5_boletosimples');
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
        $paymentMethod = $helper->getPaymentMethod();
        $isOrderNew = $order->getState() == Mage_Sales_Model_Order::STATE_NEW;
        $matchPaymentMethod = $order->getPayment()->getMethod() == $paymentMethod->getCode();

        if (!$isOrderNew || !$matchPaymentMethod) {
            return $this->norouteAction();
        }

        try {
            $bankBillet = $paymentMethod->register($order);

            $this->loadLayout();
            $this->_title($helper->__('Your order has been received'));

            $this->getLayout()->getBlock('checkout.success')
                ->setBankBillet($bankBillet);

            $this->renderLayout();
        } catch (Exception $e) {
            $exception = $helper->wrapException($e);

            Mage::logException($exception);
            Mage::log($exception->getMessage());
            Mage::getSingleton('core/session')->addError(
                $helper->__('Something went wrong. Try again later.')
            );

            $this->_redirectUrl(Mage::getUrl('checkout/cart'));
        }
    }
}
