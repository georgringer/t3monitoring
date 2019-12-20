<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang_db.xlf:tx_t3monitoring_domain_model_rule',
        'label' => 'title',
        'default_sortby' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'title',
        'iconfile' => 'EXT:t3monitoring/Resources/Public/Icons/tx_t3monitoring_domain_model_rule.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, title, description, message_category, execution_criteria, failure_criteria',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, title, description, message_category, execution_criteria, failure_criteria'],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf:labels.enabled'
                    ]
                ],
            ],
        ],

        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang_db.xlf:tx_t3monitoring_domain_model_rule.title',
            'config' => [
                'type' => 'input',
                'size' => 255,
                'eval' => 'trim'
            ],
        ],

        'description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang_db.xlf:tx_t3monitoring_domain_model_rule.description',
            'config' => [
                'type' => 'text',
                'eval' => 'trim'
            ],
        ],
        'execution_criteria' => [
            'exclude' => true,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang_db.xlf:tx_t3monitoring_domain_model_rule.execution_criteria',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'enableMultiSelectFilterTextfield' => true,
                'foreign_table' => 'tx_t3monitoring_domain_model_check',
                'MM' => 'tx_t3monitoring_rule_check_mm',
                'size' => 3,
                'autoSizeMax' => 30,
                'maxitems' => 9999,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                ],
            ],

        ],
        'failure_criteria' => [
            'exclude' => true,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang_db.xlf:tx_t3monitoring_domain_model_rule.failure_criteria',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'enableMultiSelectFilterTextfield' => true,
                'foreign_table' => 'tx_t3monitoring_domain_model_check',
                'MM' => 'tx_t3monitoring_rule_failure_criteria_check_mm',
                'size' => 3,
                'autoSizeMax' => 30,
                'maxitems' => 9999,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                ],
            ],

        ],
        'message_category' => [
            'exclude' => true,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang_db.xlf:tx_t3monitoring_domain_model_rule.message_category',
            'config' => [
                'type' => 'select',
                'size' => 1,
                'items' => [
                    ['Information', \T3Monitor\T3monitoring\Service\Import\ClientImport::MESSAGE_INFO],
                    ['Warning', \T3Monitor\T3monitoring\Service\Import\ClientImport::MESSAGE_WARNING],
                    ['Dangerous', \T3Monitor\T3monitoring\Service\Import\ClientImport::MESSAGE_DANGER]
                ]
            ],
        ],
    ],
];
