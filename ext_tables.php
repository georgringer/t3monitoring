<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extKey) {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'T3Monitor.' . $extKey,
            'T3monitor',
            'T3 Monitor'
        );

        if (TYPO3_MODE === 'BE') {
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'T3Monitor.' . $extKey,
                'tools',
                't3monitor',
                'top',
                [
                    'Statistic' => 'index,administration',
                    'Core' => 'list',
                    'Client' => 'show,fetch',
                    'Extension' => 'list, show',
                    'Sla' => 'list, show',
                ],
                [
                    'access' => 'user,group',
                    'icon' => 'EXT:' . $extKey . '/Resources/Public/Icons/module.svg',
                    'labels' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_t3monitor.xlf',
                ]
            );
        }

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3monitoring_domain_model_client');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3monitoring_domain_model_extension');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3monitoring_domain_model_core');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3monitoring_domain_model_sla');
    },
    $_EXTKEY
);
