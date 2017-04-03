<?php

class Codigo5_BoletoSimples_Helper_Info extends Codigo5_BoletoSimples_Helper_Data
{
    const LOGO_SKIN_PATH = 'adminhtml/default/default/codigo5/boletosimples/images/boletosimples.png';
    const REGISTER_URL   = 'https://boletosimples.com.br/?ref=nmylb';

    const AUTHOR_NAME           = 'Código5';
    const AUTHOR_URL            = 'https://www.codigo5.com.br';
    const AUTHOR_LOGO_SKIN_PATH = 'adminhtml/default/default/codigo5/boletosimples/images/codigo5.png';

    public function getLogoUrl()
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . self::LOGO_SKIN_PATH;
    }

    public function getRegisterUrl()
    {
        return self::REGISTER_URL;
    }

    public function getAuthorName()
    {
        return self::AUTHOR_NAME;
    }

    public function getAuthorUrl()
    {
        return self::AUTHOR_URL;
    }

    public function getAuthorLogoUrl()
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . self::AUTHOR_LOGO_SKIN_PATH;
    }
}
