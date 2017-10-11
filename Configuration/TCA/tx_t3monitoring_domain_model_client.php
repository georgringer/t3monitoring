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
        'searchFields' => 'title,domain,secret,email,php_version,mysql_version,disk_total_space,disk_free_space,insecure_core,outdated_core,insecure_extensions,outdated_extensions,error_message,extensions,backend_users,core,sla,tag',
        'iconfile' => 'EXT:t3monitoring/Resources/Public/Icons/tx_t3monitoring_domain_model_client.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, title, domain, secret, basic_auth_username, basic_auth_password, php_version, mysql_version, disk_total_space, disk_free_space, insecure_core, outdated_core, insecure_extensions, outdated_extensions, error_message, extensions, backend_users, core, sla, tag',
    ],
    'types' => [
        '1' => [
            'showitem' => '
        --div--;General,title, --palette--;;paletteDomain,email,sla,tag,
        --div--;Readonly information,last_successful_import,error_message,core, --palette--;;paletteVersions, --palette--;;paletteDiskSpace,extensions,backend_users,
                insecure_core, outdated_core, insecure_extensions, outdated_extensions,
        --div--;Extra,extra_info,extra_warning,extra_danger'
        ],
    ],
    'palettes' => [
        'paletteDomain' => ['showitem' => 'domain, secret, --linebreak--, basic_auth_username, basic_auth_password, hidden'],
        'paletteVersions' => ['showitem' => 'php_version, mysql_version'],
        'paletteDiskSpace' => ['showitem' => 'disk_total_space, disk_free_space'],
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
        'basic_auth_username' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.basic_auth_username',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'basic_auth_password' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.basic_auth_password',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,password'
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
        'tag' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.tag',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_t3monitoring_domain_model_tag',
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
        'disk_total_space' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.disk_total_space',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => 5,
                'eval' => 'int'
            ],
        ],
        'disk_free_space' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.disk_free_space',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => 5,
                'eval' => 'int'
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
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.outdated_core',
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
        'backend_users' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.backend_users',
            'config' => [
                'readOnly' => true,
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_t3monitoring_domain_model_backend_user',
                'foreign_table' => 'tx_t3monitoring_domain_model_backend_user',
                'foreign_table_where' => 'ORDER BY tx_t3monitoring_domain_model_backend_user.user_name',
                'MM' => 'tx_t3monitoring_client_backend_user_mm',
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
