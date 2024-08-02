<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\Domain\Model\Dto;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class EmMonitoringConfiguration implements SingletonInterface
{
    protected int $pid = 0;
    protected bool $loadBulletins = true;
    protected bool $useGoogleCharts = true;
    protected bool $presentationMode = false;
    protected string $ipHint = '';
    protected string $emailForFailedClient = '';
    protected int $emailAllowedAmountOfFailures = 0;

    public function __construct()
    {
        $settings = (array)GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('t3monitoring');
        $this->pid = (int)$settings['pid'];
        $this->loadBulletins = (bool)$settings['loadBulletins'];
        $this->emailForFailedClient = $settings['emailForFailedClient'];
        $this->emailAllowedAmountOfFailures = (int)$settings['emailAllowedAmountOfFailures'];
        $this->useGoogleCharts = (bool)$settings['useGoogleCharts'];
        $this->presentationMode = (bool)$settings['presentationMode'];
        $this->ipHint = $settings['ipHint'];
    }

    public function getPid(): int
    {
        return $this->pid;
    }

    public function getLoadBulletins(): bool
    {
        return $this->loadBulletins;
    }

    public function getUseGoogleCharts(): bool
    {
        return $this->useGoogleCharts;
    }

    public function isPresentationMode(): bool
    {
        return $this->presentationMode;
    }

    public function getIpHint(): string
    {
        return $this->ipHint;
    }

    public function getEmailForFailedClient(): string
    {
        return $this->emailForFailedClient;
    }

    public function getEmailAllowedAmountOfFailures(): int
    {
        return $this->emailAllowedAmountOfFailures;
    }
}
