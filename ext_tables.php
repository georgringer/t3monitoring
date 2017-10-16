<?php

defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extKey) {
        if (TYPO3_MODE === 'BE') {
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'T3Monitor.'.$extKey,
                'tools',
                't3monitor',
                'top',
                [
                    'Statistic' => 'index,administration',
                    'Core'      => 'list',
                    'Client'    => 'show,fetch',
                    'Extension' => 'list, show',
                    'Sla'       => 'list, show',
                    'Tag'       => 'list, show',
                ],
                [
                    'access' => 'user,group',
                    'icon'   => 'EXT:'.$extKey.'/Resources/Public/Icons/module.svg',
                    'labels' => 'LLL:EXT:'.$extKey.'/Resources/Private/Language/locallang_t3monitor.xlf',
                ]
            );
        }
    },
    $_EXTKEY
);
