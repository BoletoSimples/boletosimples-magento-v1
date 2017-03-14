<?php

class Codigo5_BoletoSimples_Model_Webhook_Builder extends Varien_Object
{
    public function build($events)
    {
        $this->unsetData();
        $this->addData(array(
            'url'          => $this->buildUrl(),
            'content_type' => $this->buildContentType(),
            'events'       => $this->buildEvents($events)
        ));
        return $this;
    }

    protected function buildUrl()
    {
        return Mage::getUrl('codigo5_boletosimples/payment/webhook');
    }

    protected function buildContentType()
    {
        return 'application/x-www-form-urlencoded';
    }

    protected function buildEvents($events = array())
    {
        if (!is_array($events)) {
            $events = array($events);
        }
        return $events;
    }
}
