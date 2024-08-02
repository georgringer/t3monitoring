<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core',
        'label' => 'version',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
        'searchFields' => 'version,insecure,next_secure_version,type,release_date,latest,stable,is_stable,is_active,is_latest,version_integer,is_used,is_official,',
        'iconfile' => 'EXT:t3monitoring/Resources/Public/Icons/tx_t3monitoring_domain_model_core.svg'
    ],
    'types' => [
        '1' => ['showitem' => '
         --div--;General,version,--palette--;;paletteVersion, next_secure_version, latest, stable'],
    ],
    'palettes' => [
        'paletteVersion' => ['showitem' => 'version_integer,release_date,type,
            --linebreak--,is_official,is_stable,insecure,
            --linebreak--,is_active, is_latest, is_used'],
    ],
    'columns' => [
        'version' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.version',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'insecure' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.insecure',
            'config' => [
                'type' => 'check',
                'default' => 0
            ],
        ],
        'next_secure_version' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.next_secure_version',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'type' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.type.0', 'value' => 0],
                    ['label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.type.1', 'value' => 1],
                    ['label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.type.2', 'value' => 2],
                    ['label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.type.4', 'value' => 4],
                ],
                'default' => 0,
            ],
        ],
        'release_date' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.release_date',
            'config' => [
                'dbType' => 'datetime',
                'type' => 'datetime',
            ],
        ],
        'latest' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.latest',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'stable' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.stable',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'is_stable' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.is_stable',
            'config' => [
                'type' => 'check',
                'default' => 0
            ],
        ],
        'is_active' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.is_active',
            'config' => [
                'type' => 'check',
                'default' => 0
            ],
        ],
        'is_latest' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.is_latest',
            'config' => [
                'type' => 'check',
                'default' => 0
            ],
        ],
        'version_integer' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.version_integer',
            'config' => [
                'type' => 'number',
                'size' => 4,
            ],
        ],
        'is_used' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.is_used',
            'config' => [
                'type' => 'check',
                'default' => 0,
                'readOnly' => true,
            ],
        ],
        'is_official' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_core.is_official',
            'config' => [
                'type' => 'check',
                'default' => 0
            ],
        ],
    ],
];
