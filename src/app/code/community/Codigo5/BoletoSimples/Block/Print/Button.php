<?php

class Codigo5_BoletoSimples_Block_Print_Button extends Mage_Core_Block_Template
{
    protected function _beforeToHtml()
    {
        $this->_prepareTemplate();
        $this->_prepareOrder();
        return parent::_beforeToHtml();
    }

    public function shouldOpenWindow()
    {
        return !!$this->getWindow();
    }

    public function getPrintUrl()
    {
        return $this->getUrl('codigo5_boletosimples/payment/print', array('order_id' => $this->getOrderId()));
    }

    public function getButtonBaseId()
    {
        return 'boletosimples-print-button';
    }

    public function getButtonId()
    {
        return $this->getButtonBaseId() . '-' . $this->getOrderId();
    }

    protected function _prepareTemplate()
    {
        if (!$this->hasTemplate()) {
            $this->setTemplate('codigo5/boletosimples/print/button.phtml');
        }
    }

    protected function _prepareOrder()
    {
        if ($this->hasOrderId()) {
            $order = Mage::getModel('sales/order')->load($this->getOrderId());

            if ($order->getId()) {
                $this->setOrder($order);
            }
        }
    }
}
