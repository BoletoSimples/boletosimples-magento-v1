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

        if ($order->getState() !== Mage_Sales_Model_Order::STATE_NEW || !$helper->matchPaymentMethod($order)) {
            return $this->norouteAction();
        }

        try {
            $paymentMethod = $helper->getPaymentMethod();
            $paymentMethod->process($order);

            $this->loadLayout();
            $this->_title($helper->__('Your order has been received'));

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

    public function printAction()
    {
        $helper = Mage::helper('codigo5_boletosimples');
        $order = Mage::getModel('sales/order')->loadByIncrementId($this->getRequest()->getParam('order_id'));

        if (!$order->getId() || !$helper->matchPaymentMethod($order) || !$order->getBoletosimplesBankBilletUrl()) {
            return $this->norouteAction();
        }

        $this->_redirectUrl($order->getBoletosimplesBankBilletUrl());
    }

    public function webhookAction()
    {
        if (!$this->getRequest()->isPost()) {
            return $this->norouteAction();
        }

        $helper = Mage::helper('codigo5_boletosimples/request');
        $result = array('error' => false);

        try {
            $helper->validateRequestSignature($this->getRequest());
            $helper->getPaymentMethod()->handleWebhook($helper->parseBody($this->getRequest()));
        } catch (Exception $e) {
            $exception = $helper->wrapException($e);

            Mage::logException($exception);
            Mage::log($exception->getMessage());

            $result['error'] = true;
            $result['error_message'] = $exception->getMessage();

            $this->getResponse()->setHeader('HTTP/1.1', '401 Unauthorized');
        }

        $this->getResponse()
            ->setHeader('Content-type', 'application/json', true)
            ->setBody(Mage::helper('core')->jsonEncode($result));
    }
}
