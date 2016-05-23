<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core',
        'label' => 'version',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'hideTable' => true,
        'enablecolumns' => [
        ],
        'searchFields' => 'version,insecure,next_secure_version,type,release_date,latest,stable,is_stable,is_active,is_latest,version_integer,is_used,is_official,',
        'iconfile' => 'EXT:t3monitoring/Resources/Public/Icons/tx_t3monitoring_domain_model_core.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'version, insecure, next_secure_version, type, release_date, latest, stable, is_stable, is_active, is_latest, version_integer, is_used, is_official',
    ],
    'types' => [
        '1' => ['showitem' => 'version, insecure, next_secure_version, type, release_date, latest, stable, is_stable, is_active, is_latest, version_integer, is_used, is_official, '],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [
        'version' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.version',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'insecure' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.insecure',
            'config' => [
                'type' => 'check',
                'default' => 0
            ],
        ],
        'next_secure_version' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.next_secure_version',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'type' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['-- Label --', 0],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => ''
            ],
        ],
        'release_date' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.release_date',
            'config' => [
                'dbType' => 'datetime',
                'type' => 'input',
                'size' => 12,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => '0000-00-00 00:00:00'
            ],
        ],
        'latest' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.latest',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'stable' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.stable',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'is_stable' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.is_stable',
            'config' => [
                'type' => 'check',
                'default' => 0
            ],
        ],
        'is_active' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.is_active',
            'config' => [
                'type' => 'check',
                'default' => 0
            ],
        ],
        'is_latest' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.is_latest',
            'config' => [
                'type' => 'check',
                'default' => 0
            ],
        ],
        'version_integer' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.version_integer',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ],
        ],
        'is_used' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.is_used',
            'config' => [
                'type' => 'check',
                'default' => 0
            ],
        ],
        'is_official' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.is_official',
            'config' => [
                'type' => 'check',
                'default' => 0
            ],
        ],
    ],
];
