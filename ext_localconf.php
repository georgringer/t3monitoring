<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extKey) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter(\T3Monitor\T3monitoring\Domain\TypeConverter\ClientFilterDemandConverter::class);

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbBackend::class]
            = array(
            'className' => \T3Monitor\T3monitoring\Xclass\Typo3DbBackendXclassed::class
        );

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][]
            = \T3Monitor\T3monitoring\Command\MonitoringCommandController::class;
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][]
            = \T3Monitor\T3monitoring\Command\ReportCommandController::class;
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$extKey]
            = \T3Monitor\T3monitoring\Hooks\DataHandlerHook::class;
    },
    $_EXTKEY
);
