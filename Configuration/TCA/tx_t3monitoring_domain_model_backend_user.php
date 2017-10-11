<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_backend_user',
        'label' => 'real_name',
        'label_alt' => 'user_name',
        'label_alt_force' => true,
        'hideTable' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'user_name,real_name,email_address,last_login,description,',
        'iconfile' => 'EXT:t3monitoring/Resources/Public/Icons/tx_t3monitoring_domain_model_backend_user.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, user_name, real_name, email_address, description, last_login',
    ],
    'types' => [
        '1' => [
            'showitem' => '
        --div--;Readonly information,user_name, real_name, email_address, description, last_login'
        ],
    ],
    'palettes' => [
    ],
    'columns' => [

        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],

        'user_name' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_backend_user.user_name',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
                'max' => 255
            ],

        ],
        'email_address' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_backend_user.email_address',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'max' => 255
            ],

        ],
        'real_name' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_backend_user.real_name',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
                'max' => 255
            ],

        ],
        'description' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_backend_user.description',
            'config' => [
                'readOnly' => true,
                'type' => 'text',
                'default' => '',
                'cols' => 40,
                'rows' => 5,
            ],

        ],
        'last_login' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:tx_t3monitoring_domain_model_backend_user.last_login',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'datetime'
            ],

        ],
    ],
];
