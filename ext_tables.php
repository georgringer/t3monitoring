<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {
        if (TYPO3_MODE === 'BE') {
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'T3Monitor.t3monitoring',
                'tools',
                't3monitor',
                'top',
                [
                    'Statistic' => 'index,administration',
                    'Core' => 'list',
                    'Client' => 'show,fetch',
                    'Extension' => 'list, show',
                    'Sla' => 'list, show',
                    'Tag' => 'list, show',
                ],
                [
                    'access' => 'user,group',
                    'icon' => 'EXT:t3monitoring/Resources/Public/Icons/module.svg',
                    'labels' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang_t3monitor.xlf',
                ]
            );
        }
    }
);
