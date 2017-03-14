<?php

class Codigo5_BoletoSimples_Helper_Hash extends Codigo5_BoletoSimples_Helper_Data
{
    public function hashEquals($hash1, $hash2)
    {
        if (strlen($hash1) != strlen($hash2)) {
            return false;
        }

        $res = $hash1 ^ $hash2;
        $ret = 0;

        for ($i = strlen($res) - 1; $i >= 0; $i--) {
            $ret |= ord($res[$i]);
        }

        return !$ret;
    }
}
