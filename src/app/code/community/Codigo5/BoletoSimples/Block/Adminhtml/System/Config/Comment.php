<?php

class Codigo5_BoletoSimples_Block_Adminhtml_System_Config_Comment extends Mage_Core_Block_Template
{
    public function getVersion()
    {
        return $this->_getHelper()->getVersion();
    }

    public function getLogoUrl()
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . 'adminhtml/default/default/codigo5/boletosimples/images/boletosimples.png';
    }

    public function getRegisterUrl()
    {
        return 'https://boletosimples.com.br/users/sign_up';
    }

    public function getAuthorName()
    {
        return $this->_getHelper()->getAuthorName();
    }

    public function getAuthorUrl()
    {
        return $this->_getHelper()->getAuthorUrl();
    }

    public function getAuthorLogoUrl()
    {
        return $this->_getHelper()->getAuthorLogoUrl();
    }

    protected function _getHelper()
    {
        return Mage::helper('codigo5_boletosimples');
    }
}
