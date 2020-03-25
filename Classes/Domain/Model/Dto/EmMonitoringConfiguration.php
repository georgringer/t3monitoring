<?php

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

/**
 * Extension configuration
 */
class EmMonitoringConfiguration implements SingletonInterface
{

    /** @var int */
    protected $pid = 0;

    /** @var bool */
    protected $loadBulletins = true;

    /** @var bool */
    protected $useGoogleCharts = true;

    /** @var bool */
    protected $presentationMode = false;

    /** @var string */
    protected $ipHint = '';

    /** @var string */
    protected $emailForFailedClient = '';

    /** @var int */
    protected $emailAllowedAmountOfFailures = 0;

    public function __construct()
    {
        $settings = (array)GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('t3monitoring');
        foreach ($settings as $key => $value) {
            if (property_exists(__CLASS__, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @return int
     */
    public function getPid(): int
    {
        return (int)$this->pid;
    }

    /**
     * @return bool
     */
    public function getLoadBulletins(): bool
    {
        return (bool)$this->loadBulletins;
    }

    /**
     * @return bool
     */
    public function getUseGoogleCharts(): bool
    {
        return (bool)$this->useGoogleCharts;
    }

    /**
     * @return bool
     */
    public function isPresentationMode(): bool
    {
        return $this->presentationMode;
    }

    /**
     * @return string
     */
    public function getIpHint(): string
    {
        return $this->ipHint;
    }

    /**
     * @return string
     */
    public function getEmailForFailedClient(): string
    {
        return $this->emailForFailedClient;
    }

    /**
     * @return int
     */
    public function getEmailAllowedAmountOfFailures(): int
    {
        return $this->emailAllowedAmountOfFailures;
    }
}
