<?php

class Codigo5_BoletoSimples_Helper_Webservice extends Codigo5_BoletoSimples_Helper_Data
{
    public function checkAuth()
    {
        try {
            $this->ensureLibrariesLoad();
            $user = BoletoSimples\Extra::userinfo();

            Mage::getSingleton('core/session')->addSuccess(
                $this->buildMessage($this->__('Hello %s, we have successfully saved your credentials.', $user['full_name']))
            );
        } catch (Exception $e) {
            throw new Codigo5_BoletoSimples_Exception(
                $this->__('Could not authenticate > %s', $e->getMessage())
            );
        }
    }

    public function checkWebhooks()
    {
        try {
            $this->ensureLibrariesLoad();

            $createdWebhooks = $this->getConfig('webhooks');
            $createdWebhooks = (is_null($createdWebhooks)) ? array() : Mage::helper('core')->jsonDecode($createdWebhooks);

            // Check missing webhooks
            $missingEvents = array_diff($this->getPaymentMethod()->getSupportedWebhooksEvents(), array_keys($createdWebhooks));

            if (count($missingEvents)) {
                $builder = Mage::getModel('codigo5_boletosimples/webhook_builder')->build($missingEvents);
                $newWebhook = BoletoSimples\Webhook::create($builder->getData());

                if (!$newWebhook->isPersisted()) {
                    throw new Codigo5_BoletoSimples_Exception(
                        $this->humanizeResourceErrors($newWebhook)
                    );
                }

                $newWebhooks = array_combine(
                    $missingEvents,
                    array_fill(0, count($missingEvents), $newWebhook->id)
                );
                $createdWebhooks = array_merge($createdWebhooks, $newWebhooks);

                $this->saveConfig('webhooks', Mage::helper('core')->jsonEncode($createdWebhooks));
            }

            Mage::getSingleton('core/session')->addSuccess(
                $this->buildMessage($this->__('Webhooks have been successfully checked.'))
            );
        } catch (Exception $e) {
            throw new Codigo5_BoletoSimples_Exception(
                $this->__('Could not check the webhooks > %s', $e->getMessage())
            );
        }
    }

    public function humanizeResourceErrors(BoletoSimples\BaseResource $resource)
    {
        $errors = array_filter($resource->response_errors);
        $errors = array_map(function($errors, $key) {
            if (is_array($errors)) {
                $errors = implode(', ', $errors);
            }
            return "'{$key}' {$errors}";
        }, $errors, array_keys($errors));

        return implode(' / ', $errors);
    }
}
