<?php
defined('TYPO3') || die('Access denied.');

call_user_func(
    function () {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
            'T3monitoring',
            'tools',
            't3monitor',
            'top',
            [
                \T3Monitor\T3monitoring\Controller\StatisticController::class => 'index,administration',
                \T3Monitor\T3monitoring\Controller\CoreController::class => 'list',
                \T3Monitor\T3monitoring\Controller\ClientController::class => 'show,fetch',
                \T3Monitor\T3monitoring\Controller\ExtensionController::class => 'list, show',
                \T3Monitor\T3monitoring\Controller\SlaController::class => 'list, show',
                \T3Monitor\T3monitoring\Controller\TagController::class => 'list, show',
            ],
            [
                'access' => 'user,group',
                'icon' => 'EXT:t3monitoring/Resources/Public/Icons/module.svg',
                'labels' => 'LLL:EXT:t3monitoring/Resources/Private/Language/locallang_t3monitor.xlf',
            ]
        );
    }
);
