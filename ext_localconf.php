<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extKey) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter(\T3Monitor\T3monitoring\Domain\TypeConverter\ClientFilterDemandConverter::class);

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][]
            = \T3Monitor\T3monitoring\Command\MonitoringCommandController::class;
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][]
            = \T3Monitor\T3monitoring\Command\ReportCommandController::class;
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$extKey]
            = \T3Monitor\T3monitoring\Hooks\DataHandlerHook::class;

        $GLOBALS['TYPO3_CONF_VARS']['EXT'][$extKey]['resolver']['configurationValue'] = \T3Monitor\T3monitoring\Resolver\ConfigurationResolver::class;
        $GLOBALS['TYPO3_CONF_VARS']['EXT'][$extKey]['resolver']['extensionState'] = \T3Monitor\T3monitoring\Resolver\ExtensionStateResolver::class;
        $GLOBALS['TYPO3_CONF_VARS']['EXT'][$extKey]['resolver']['extensionVersion'] = \T3Monitor\T3monitoring\Resolver\ExtensionVersionResolver::class;
        $GLOBALS['TYPO3_CONF_VARS']['EXT'][$extKey]['resolver']['coreVersion'] = \T3Monitor\T3monitoring\Resolver\CoreVersionResolver::class;
    },
    't3monitoring'
);
