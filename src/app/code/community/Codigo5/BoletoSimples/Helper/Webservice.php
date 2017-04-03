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

            $currentWebhookSecretKey = $this->getConfig('webhook_secret_key');

            if (empty($currentWebhookSecretKey)) {
                $currentWebhookEvents = array_filter(explode(',', $this->getConfig('webhook_events')));

                // Check missing webhooks
                $missingEvents = array_diff($this->getPaymentMethod()->getSupportedWebhooksEvents(), $currentWebhookEvents);

                if (count($missingEvents)) {
                    $builder = Mage::getModel('codigo5_boletosimples/webhook_builder')->build($missingEvents);
                    $newWebhook = BoletoSimples\Webhook::create($builder->getData());

                    if (!$newWebhook->isPersisted()) {
                        throw new Codigo5_BoletoSimples_Exception(
                            $this->humanizeResourceErrors($newWebhook)
                        );
                    }

                    $currentWebhookEvents = array_merge($currentWebhookEvents, $missingEvents);

                    $this->saveConfig('webhook_secret_key', $newWebhook->secret_key);
                    $this->saveConfig('webhook_events', implode(',', $currentWebhookEvents));
                }
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
