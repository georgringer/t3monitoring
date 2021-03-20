<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_task',
        'label' => 'description',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'searchFields' => 'description, lastexecution_failure',
        'iconfile' => 'EXT:t3monitoring/Resources/Public/Icons/tx_t3monitoring_domain_model_task.svg',
    ],
    'interface' => [
        'showRecordFieldList' => 'client_task_uid, description, nextexecution, lastexecution, lastexecution_failure, lastexecution_context, class, multiple, type, frequency, client, late',
    ],
    'types' => [
        '1' => [
            'showitem' => 'client_task_uid, description, nextexecution, lastexecution, lastexecution_failure, lastexecution_context, class, multiple, type, frequency, client, late'],
    ],
    'columns' => [
        'client_task_uid' => [
            'exclude' => false,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_task.client_task_uid',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim,required',
                'readOnly' => 'true'
            ],
        ],
        'description' => [
            'exclude' => false,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_task.description',
            'config' => [
                'readOnly' => 'true',
                'type' => 'text',
                'default' => '',
                'enableRichtext' => 'false'
            ],
        ],
        'nextexecution' => [
            'exclude' => false,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_task.nextexecution',
            'config' => [
                'readOnly' => 'true',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'default' => 0,
                'eval' => 'datetime'
            ],
        ],
        'lastexecution' => [
            'exclude' => false,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_task.lastexecution',
            'config' => [
                'readOnly' => 'true',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'default' => 0,
                'eval' => 'datetime'
            ],
        ],
        'lastexecution_failure' => [
            'exclude' => false,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_task.lastexecution_failure',
            'config' => [
                'readOnly' => 'true',
                'type' => 'text',
                'default' => ''
            ],
        ],
        'lastexecution_context' => [
            'exclude' => false,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_task.lastexecution_context',
            'config' => [
                'readOnly' => 'true',
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'class' => [
            'exclude' => false,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_task.class',
            'config' => [
                'readOnly' => 'true',
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'type' => [
            'exclude' => false,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_task.type',
            'config' => [
                'readOnly' => 'true',
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_task.type.1:', '1'],
                    ['LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_task.type.2', '2'],
                ],
            ],
        ],
        'frequency' => [
            'exclude' => false,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_task.frequency',
            'config' => [
                'readOnly' => 'true',
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'client' => [
            'exclude' => false,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_task.client',
            'config' => [
                'readOnly' => 'true',
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_t3monitoring_domain_model_client',
                'minitems' => 1,
                'maxitems' => 1
            ],
        ],
        'late' => [
            'exclude' => false,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_task.late',
            'config' => [
                'readOnly' => 'true',
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    '0' => [
                        '0' => '',
                        '1' => 'late',
                        'labelChecked' => 'Enabled',
                        'labelUnchecked' => 'Disabled'
                    ]
                ]
            ]
        ]
    ],
];
