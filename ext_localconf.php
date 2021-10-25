<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter(
            \T3Monitor\T3monitoring\Domain\TypeConverter\ClientFilterDemandConverter::class
        );

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['t3monitoring']
            = \T3Monitor\T3monitoring\Hooks\DataHandlerHook::class;
    }
);
