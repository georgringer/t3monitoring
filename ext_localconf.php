<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {
        $isv10 = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger('10.0')
            <= \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_branch);
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter(
            $isv10
            ? \T3Monitor\T3monitoring\Domain\TypeConverter\ClientFilterDemandConverterV10::class
            : \T3Monitor\T3monitoring\Domain\TypeConverter\ClientFilterDemandConverterV9::class
        );

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['t3monitoring']
            = \T3Monitor\T3monitoring\Hooks\DataHandlerHook::class;
    }
);
