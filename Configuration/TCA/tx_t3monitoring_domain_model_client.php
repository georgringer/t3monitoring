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
        'searchFields' => 'title,domain,secret,email,php_version,mysql_version,disk_total_space,disk_free_space,insecure_core,outdated_core,insecure_extensions,outdated_extensions,error_message,extensions,core,sla,tag',
        'iconfile' => 'EXT:t3monitoring/Resources/Public/Icons/tx_t3monitoring_domain_model_client.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, title, domain, secret, basic_auth_username, basic_auth_password, host_header, ignore_cert_errors, force_ip_resolve, php_version, mysql_version, disk_total_space, disk_free_space, insecure_core, outdated_core, insecure_extensions, outdated_extensions, error_message, extensions, core, sla, tag',
    ],
    'types' => [
        1 => [
            'showitem' => '
        --div--;General,--palette--;;paletteTitle, --palette--;;paletteDomain,email,sla,tag,
        --div--;Readonly information,last_successful_import,error_message,--palette--;;paletteCore, --palette--;;paletteExtensions, --palette--;;paletteVersions, --palette--;;paletteDiskSpace,
        --div--;Extra,extra_info,extra_warning,extra_danger,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                hidden,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,'
        ],
    ],
    'palettes' => [
        'paletteTitle' => ['showitem' => 'title'],
        'paletteCore' => ['showitem' => 'core, insecure_core, outdated_core,'],
        'paletteExtensions' => ['showitem' => 'extensions, --linebreak--, insecure_extensions, outdated_extensions,'],
        'paletteDomain' => ['showitem' => 'domain, secret, --linebreak--, basic_auth_username, basic_auth_password, host_header, --linebreak--, ignore_cert_errors, force_ip_resolve'],
        'paletteVersions' => ['showitem' => 'php_version, mysql_version'],
        'paletteDiskSpace' => ['showitem' => 'disk_total_space, disk_free_space'],
    ],
    'columns' => [
        'hidden' => [
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ],
        ],
        'title' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
                'max' => 255
            ],
        ],
        'domain' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.domain',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim,required',
                'placeholder' => 'http://yourdomain.com/'
            ],
        ],
        'secret' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.secret',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim,required',
                'min' => 5,
                'max' => 255
            ],
        ],
        'basic_auth_username' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.basic_auth_username',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'basic_auth_password' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.basic_auth_password',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,password'
            ],
        ],
        'host_header' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.hostHeader',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'placeholder' => 'app.myproject.com'
            ],
        ],
        'ignore_cert_errors' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.ignoreCertErrors',
            'config' => [
                'type' => 'check',
            ],
        ],
        'force_ip_resolve' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.forceIpResolve',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', ''],
                    ['IPv4', 'v4'],
                    ['IPv6', 'v6'],
                ],
                'default' => '',
            ],
        ],
        'email' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'placeholder' => 'notification@client.com',
                'eval' => 'trim'
            ],
        ],
        'sla' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.sla',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_t3monitoring_domain_model_sla',
                'minitems' => 0,
                'default' => 0,
                'items' => [
                    ['', 0]
                ]
            ],
        ],
        'tag' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.tag',
            'config' => [
                'enableMultiSelectFilterTextfield' => 1,
                'type' => 'select',
                'default' => '',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_t3monitoring_domain_model_tag',
                'minitems' => 0,
                'maxitems' => 10,
            ],
        ],
        'php_version' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.php_version',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => 5,
                'eval' => 'trim'
            ],
        ],
        'mysql_version' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.mysql_version',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => 5,
                'eval' => 'trim'
            ],
        ],
        'disk_total_space' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.disk_total_space',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => 5,
                'eval' => 'int'
            ],
        ],
        'disk_free_space' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.disk_free_space',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => 5,
                'eval' => 'int'
            ],
        ],
        'insecure_core' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.insecure_core',
            'config' => [
                'readOnly' => true,
                'type' => 'check',
                'default' => 0
            ],
        ],
        'outdated_core' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.outdated_core',
            'config' => [
                'readOnly' => true,
                'type' => 'check',
                'default' => 0
            ],
        ],
        'insecure_extensions' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.insecure_extensions',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ],
        ],
        'outdated_extensions' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.outdated_extensions',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ],
        ],
        'error_message' => [
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.error_message',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'extra_info' => [
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
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.last_successful_import',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
                'default' => 0,
                'size' => 10,
            ],
        ],
        'extensions' => [
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
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_client.core',
            'config' => [
                'readOnly' => true,
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_t3monitoring_domain_model_core',
                'minitems' => 0,
            ],
        ],
    ],
];
