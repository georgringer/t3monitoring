<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {
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
                'Task' => 'list'
            ],
            [
                'access' => 'user,group',
                'icon' => 'EXT:t3monitoring/Resources/Public/Icons/module.svg',
                'labels' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang_t3monitor.xlf',
            ]
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3monitoring_domain_model_client');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3monitoring_domain_model_core');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3monitoring_domain_model_extension');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3monitoring_domain_model_sla');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3monitoring_domain_model_tag');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3monitoring_domain_model_task');
    }
);
