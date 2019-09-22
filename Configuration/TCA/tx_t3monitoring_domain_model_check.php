<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang_db.xlf:tx_t3monitoring_domain_model_check',
        'label' => 'title',
        'default_sortby' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'type' => 'type',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'title',
        'iconfile' => 'EXT:t3monitoring/Resources/Public/Icons/tx_t3monitoring_domain_model_check.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, title, type, argument, operator, value',
    ],
    'types' => [
        '1' => [
            'showitem' => 'hidden, title, type, argument, operator, value',
        ],
        'configurationValue' => [
            'showitem' => 'hidden, title, type, argument, operator, value',
            'columnsOverrides' => [
                'operator' => [
                    'config' => [
                        'items' => [
                            ['is', 'is'],
                            ['is not', 'isNot'],
                            ['is true', 'isTrue'],
                            ['is false', 'isFalse'],
                        ]
                    ]
                ]
            ]
        ],
        'header' => [
            'showitem' => 'hidden, title, type, argument, operator',
            'columnsOverrides' => [
                'operator' => [
                    'config' => [
                        'items' => [
                            ['is set', 'isSet'],
                            ['is not set', 'isNotSet'],
                        ]
                    ]
                ]
            ]
        ],
        'coreVersion' => [
            'showitem' => 'hidden, title, type, operator, value',
            'columnsOverrides' => [
                'operator' => [
                    'config' => [
                        'items' => [
                            ['matches', 'matchesVersion'],
                            ['does not matches', 'matchesNotVersion'],
                        ]
                    ]
                ],
                'value' => [
                    'label' => 'Composer version constraint'
                ]
            ]
        ],
        'extensionState' => [
            'showitem' => 'hidden, title, type, argument, operator',
            'columnsOverrides' => [
                'operator' => [
                    'config' => [
                        'items' => [
                            ['is present', 'isPresent'],
                            ['is not present', 'isNotPresent'],
                            ['is loaded', 'isLoaded'],
                            ['is not loaded', 'isNotLoaded'],
                        ]
                    ]
                ],
                'argument' => [
                    'label' => 'Extension key'
                ]
            ]
        ],
        'extensionVersion' => [
            'showitem' => 'hidden, title, type, operator, value',
            'columnsOverrides' => [
                'operator' => [
                    'config' => [
                        'items' => [
                            ['matches', 'matchesVersion'],
                            ['does not matches', 'matchesNotVersion'],
                        ]
                    ]
                ],
                'value' => [
                    'label' => 'Composer version constraint'
                ]
            ]
        ],
        'backendUser' => [
            'showitem' => 'hidden, title, type, argument, operator',
            'columnsOverrides' => [
                'argument' => [
                    'label' => 'List of backend users separated by newline',
                    'config' => [
                        'type' => 'text'
                    ]
                ],
                'operator' => [
                    'config' => [
                        'items' => [
                            ['is active', 'isActive'],
                        ]
                    ]
                ],
            ]
        ],
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
        'type' => [
            'exclude' => true,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang_db.xlf:tx_t3monitoring_domain_model_check.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'itemsProcFunc' => \T3Monitor\T3monitoring\Utility\TcaUtility::class . '->getCheckTypes',
                'size' => 1,
                'maxitems' => 1,
                'onChange' => 'reload'
            ],
        ],
        'argument' => [
            'exclude' => true,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang_db.xlf:tx_t3monitoring_domain_model_check.argument',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'operator' => [
            'exclude' => true,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang_db.xlf:tx_t3monitoring_domain_model_check.operator',
            'config' => [
                'required' => 1,
                'type' => 'select',
                'renderType' => 'selectSingle',
                'size' => 1,
                'maxitems' => 1,
                'items' => []
            ],
        ],
        'value' => [
            'exclude' => true,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang_db.xlf:tx_t3monitoring_domain_model_check.value',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],

    ],
];
