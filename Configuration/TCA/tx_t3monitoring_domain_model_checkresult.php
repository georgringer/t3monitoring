<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang_db.xlf:tx_t3monitoring_domain_model_checkresult',
        'crdate' => 'crdate',
        'hideTable' => true,
        'iconfile' => 'EXT:t3monitoring/Resources/Public/Icons/tx_t3monitoring_domain_model_rule.gif'
    ],
    'columns' => [
        'client' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_t3monitoring_domain_model_client',
                'maxitems' => 1,
            ],

        ],
        'failed_rules' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_t3monitoring_domain_model_rule',
                'MM' => 'tx_t3monitoring_checkresult_failed_rules_mm',
                'maxitems' => 9999,
            ],
        ],
        'missing_provider_data' => [
            'config' => [
                'readOnly' => true,
                'type' => 'check',
                'default' => 0
            ],
        ],
    ],
];
