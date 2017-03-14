<?php

class Codigo5_BoletoSimples_Helper_Payment extends Codigo5_BoletoSimples_Helper_Data
{
    public function getStateByStatus($status)
    {
        $status = Mage::getResourceModel('sales/order_status_collection')
            ->addStatusFilter($status)
            ->getFirstItem();

        return $status->getState();
    }
}
