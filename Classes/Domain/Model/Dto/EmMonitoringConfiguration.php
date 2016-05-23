<?php
namespace T3Monitor\T3monitoring\Domain\Model\Dto;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\SingletonInterface;

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
    protected $presentationMode = false;

    /** @var string */
    protected $ipHint = '';

    public function __construct()
    {
        $settings = (array)unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['t3monitoring']);
        foreach ($settings as $key => $value) {
            if (property_exists(__CLASS__, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @return int
     */
    public function getPid()
    {
        return (int)$this->pid;
    }

    /**
     * @return bool
     */
    public function getLoadBulletins()
    {
        return (bool)$this->loadBulletins;
    }

    /**
     * @return bool
     */
    public function isPresentationMode()
    {
        return $this->presentationMode;
    }

    /**
     * @return string
     */
    public function getIpHint()
    {
        return $this->ipHint;
    }
}
