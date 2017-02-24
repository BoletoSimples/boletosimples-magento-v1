<?php

abstract class Codigo5_BoletoSimples_Model_System_Config_Comment_Abstract
{
    public function getCommentText()
    {
        return $this->getCommentBlock()->toHtml();
    }

    public function getCommentBlock()
    {
        return Mage::app()->getLayout()
            ->createBlock($this->getCommentBlockName())
            ->setTemplate($this->getCommentBlockTemplate());
    }

    public function getCommentBlockName()
    {
        return 'codigo5_boletosimples/adminhtml_system_config_comment';
    }

    abstract public function getCommentBlockTemplate();
}
