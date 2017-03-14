<?php

$installer = $this;
$installer->startSetup();

// Common variables
$helper = Mage::helper('codigo5_boletosimples');

//
// Setup Regions
/////////////////////////////////////////////////
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

//
// Setup Order Attributes
/////////////////////////////////////////////////
$setup = new Mage_Sales_Model_Resource_Setup('core_setup');
$setup
    ->addAttribute('order', 'boletosimples_bank_billet_id', array('type' => 'varchar'))
    ->addAttribute('order', 'boletosimples_bank_billet_url', array('type' => 'varchar'));

//
// Setup Order Statuses
/////////////////////////////////////////////////

// Insert statuses
$installer->getConnection()->insertArray(
    $installer->getTable('sales/order_status'),
    array(
        'status',
        'label'
    ),
    array(
        array(
            'status' => 'boletosimples_pending',
            'label' => $helper->__('Pending')
        ),
        array(
            'status' => 'boletosimples_waiting_payment',
            'label' => $helper->__('Waiting Payment')
        ),
        array(
            'status' => 'boletosimples_paid',
            'label' => $helper->__('Paid')
        )
    )
);

// Insert states and mapping of statuses to states
$installer->getConnection()->insertArray(
    $installer->getTable('sales/order_status_state'),
    array(
        'status',
        'state',
        'is_default'
    ),
    array(
        array(
            'status'     => 'boletosimples_pending',
            'state'      => Mage_Sales_Model_Order::STATE_NEW,
            'is_default' => 1
        ),
        array(
            'status'     => 'boletosimples_waiting_payment',
            'state'      => Mage_Sales_Model_Order::STATE_PENDING_PAYMENT,
            'is_default' => 0
        ),
        array(
            'status'     => 'boletosimples_paid',
            'state'      => Mage_Sales_Model_Order::STATE_PROCESSING,
            'is_default' => 0
        )
    )
);

$installer->endSetup();
