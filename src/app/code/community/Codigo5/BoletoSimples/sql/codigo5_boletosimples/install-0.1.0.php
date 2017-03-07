<?php

$installer = $this;
$installer->startSetup();

// Setup Regions
$countries = array(
    'BR' => array(
        'AC' => 'Acre',
        'AL' => 'Alagoas',
        'AP' => 'Amapá',
        'AM' => 'Amazonas',
        'BA' => 'Bahia',
        'CE' => 'Ceará',
        'ES' => 'Espírito Santo',
        'GO' => 'Goiás',
        'MA' => 'Maranhão',
        'MT' => 'Mato Grosso',
        'MS' => 'Mato Grosso do Sul',
        'MG' => 'Minas Gerais',
        'PA' => 'Pará',
        'PB' => 'Paraíba',
        'PR' => 'Paraná',
        'PE' => 'Pernambuco',
        'PI' => 'Piauí',
        'RJ' => 'Rio de Janeiro',
        'RN' => 'Rio Grande do Norte',
        'RS' => 'Rio Grande do Sul',
        'RO' => 'Rondônia',
        'RR' => 'Roraima',
        'SC' => 'Santa Catarina',
        'SP' => 'São Paulo',
        'SE' => 'Sergipe',
        'TO' => 'Tocantins',
        'DF' => 'Distrito Federal'
    )
);

foreach ($countries as $countryId => $regions) {
    foreach ($regions as $code => $defaultName) {
        $region = Mage::getModel('directory/region')->loadByCode($code, $countryId);

        if ($region->getId()) {
            continue;
        }

        $region
            ->setData(array(
                'country_id' => $countryId,
                'code' => $code,
                'default_name' => $defaultName
            ))
            ->save();
    }
}

// Setup Order Attributes
$setup = new Mage_Sales_Model_Resource_Setup('core_setup');
$setup
    ->addAttribute('order', 'boletosimples_bank_billet_id', array('type' => 'varchar'))
    ->addAttribute('order', 'boletosimples_bank_billet_url', array('type' => 'varchar'));

$installer->endSetup();
