<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\Service\Import;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Psr\EventDispatcher\EventDispatcherInterface;
use T3Monitor\T3monitoring\Domain\Model\Dto\EmMonitoringConfiguration;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class BaseImport
{
    protected EmMonitoringConfiguration $emConfiguration;
    protected Registry $registry;
    protected EventDispatcherInterface $eventDispatcher;

    public function __construct()
    {
        $this->emConfiguration = GeneralUtility::makeInstance(EmMonitoringConfiguration::class);
        $this->registry = GeneralUtility::makeInstance(Registry::class);
        $this->eventDispatcher = GeneralUtility::makeInstance(EventDispatcherInterface::class);
    }

    protected function setImportTime(string $action): void
    {
        $now = GeneralUtility::makeInstance(Context::class)->getAspect('date')->get('timestamp');
        $this->registry->set('t3monitoring', 'import' . ucfirst($action), $now);
    }
}
