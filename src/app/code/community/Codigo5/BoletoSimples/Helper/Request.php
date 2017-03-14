<?php

class Codigo5_BoletoSimples_Helper_Request extends Codigo5_BoletoSimples_Helper_Data
{
    public function generateRequestHash(Zend_Controller_Request_Http $request, $algorithm)
    {
        return hash_hmac($algorithm, $request->getRawBody(), $this->getPaymentMethod()->getConfigData('access_token'));
    }

    public function validateRequestSignature(Zend_Controller_Request_Http $request)
    {
        $signature = $request->getServer('HTTP_X_HUB_SIGNATURE');

        if (is_null($signature)) {
            throw new Codigo5_BoletoSimples_Exception($this->__("HTTP header 'X-Hub-Signature' is missing."));
        } elseif (!extension_loaded('hash')) {
            throw new Codigo5_BoletoSimples_Exception($this->__("Missing 'hash' extension to check the secret code validity."));
        }

        list($algorithm, $hash) = explode('=', $signature, 2) + array('', '');

        if (!in_array($algorithm, hash_algos(), true)) {
            throw new Codigo5_BoletoSimples_Exception($this->__("Hash algorithm '%s' is not supported.", $algorithm));
        }

        $helper = Mage::helper('codigo5_boletosimples/hash');
        $generatedHash = $this->generateRequestHash($request, $algorithm);

        if (!$helper->hashEquals($generatedHash, $hash)) {
            throw new Codigo5_BoletoSimples_Exception($this->__('Hook secret does not match.'));
        }
    }

    public function parseBody(Zend_Controller_Request_Http $request)
    {
        switch ($request->getHeader('Content-Type')) {
            case 'application/json':
                return Mage::helper('core')->jsonDecode($request->getRawBody());

            case 'application/x-www-form-urlencoded':
                return $request->getPost('payload');
        }
    }
}
