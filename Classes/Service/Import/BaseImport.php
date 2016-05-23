<?php
namespace T3Monitor\T3monitoring\Service\Import;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Dto\EmMonitoringConfiguration;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class BaseImport
 */
class BaseImport
{
    /** @var EmMonitoringConfiguration */
    protected $emConfiguration;

    /** @var Registry */
    protected $registry;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->emConfiguration = GeneralUtility::makeInstance(EmMonitoringConfiguration::class);
        $this->registry = GeneralUtility::makeInstance(Registry::class);
    }

    /**
     * @param string $action
     * @throws \InvalidArgumentException
     */
    protected function setImportTime($action)
    {
        $this->registry->set('t3monitoring', 'import' . ucfirst($action), $GLOBALS['EXEC_TIME']);
    }

    /**
     * @return DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
