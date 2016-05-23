<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'title,domain,secret,email,php_version,mysql_version,insecure_core,outdated_core,insecure_extensions,outdated_extensions,error_message,extensions,core,sla,',
        'iconfile' => 'EXT:t3monitoring/Resources/Public/Icons/tx_t3monitoring_domain_model_client.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, title, domain, secret, php_version, mysql_version, insecure_core, outdated_core, insecure_extensions, outdated_extensions, error_message, extensions, core, sla',
    ],
    'types' => [
        '1' => [
            'showitem' => '
        --div--;General,title, --palette--;;paletteDomain,email,sla,
        --div--;Readonly information,last_successful_import,error_message,core, --palette--;;paletteVersions,extensions,
                insecure_core, outdated_core, insecure_extensions, outdated_extensions,
        --div--;Extra,extra_info,extra_warning,extra_danger'
        ],
    ],
    'palettes' => [
        'paletteDomain' => ['showitem' => 'domain, secret,hidden'],
        'paletteVersions' => ['showitem' => 'php_version, mysql_version'],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'title' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
                'max' => 255
            ],
        ],
        'domain' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.domain',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
                'placeholder' => 'http://yourdomain.com/'
            ],
        ],
        'secret' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.secret',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
                'min' => 5,
                'max' => 255
            ],
        ],
        'email' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'placeholder' => 'notification@client.com',
                'eval' => 'trim'
            ],
        ],
        'sla' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.sla',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_t3monitoring_domain_model_sla',
                'minitems' => 0,
                'maxitems' => 1,
                'default' => 0,
                'items' => [
                    ['', 0]
                ]
            ],
        ],
        'php_version' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.php_version',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => 5,
                'eval' => 'trim'
            ],
        ],
        'mysql_version' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.mysql_version',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => 5,
                'eval' => 'trim'
            ],
        ],
        'insecure_core' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.insecure_core',
            'config' => [
                'readOnly' => true,
                'type' => 'check',
                'default' => 0
            ],
        ],
        'outdated_core' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.insecure_extensions',
            'config' => [
                'readOnly' => true,
                'type' => 'check',
                'default' => 0
            ],
        ],
        'insecure_extensions' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.insecure_extensions',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ],
        ],
        'outdated_extensions' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.outdated_extensions',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ],
        ],
        'error_message' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.error_message',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'extra_info' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang_db.xlf:tx_t3monitoring_domain_model_client.extra_info',
            'config' => [
                'readOnly' => true,
                'type' => 'text',
                'default' => '',
                'cols' => 40,
                'rows' => 5,
            ],
        ],
        'extra_warning' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang_db.xlf:tx_t3monitoring_domain_model_client.extra_warning',
            'config' => [
                'readOnly' => true,
                'type' => 'text',
                'default' => '',
                'cols' => 40,
                'rows' => 5,
            ],
        ],
        'extra_danger' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang_db.xlf:tx_t3monitoring_domain_model_client.extra_danger',
            'config' => [
                'readOnly' => true,
                'type' => 'text',
                'default' => '',
                'cols' => 40,
                'rows' => 5,
            ],
        ],
        'last_successful_import' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.last_successful_import',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => 10,
                'eval' => 'datetime',
                'checkbox' => 1,
                'default' => 0
            ],
        ],
        'extensions' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.extensions',
            'config' => [
                'readOnly' => true,
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_t3monitoring_domain_model_extension',
                'foreign_table' => 'tx_t3monitoring_domain_model_extension',
                'foreign_table_where' => 'ORDER BY tx_t3monitoring_domain_model_extension.name',
                'MM' => 'tx_t3monitoring_client_extension_mm',
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 9999,
                'multiple' => 0,
            ],
        ],
        'core' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.core',
            'config' => [
                'readOnly' => true,
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_t3monitoring_domain_model_core',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
    ],
];
