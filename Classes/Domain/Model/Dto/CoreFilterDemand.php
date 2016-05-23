<?php
namespace T3Monitor\T3monitoring\Domain\Model\Dto;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Class CoreFilterDemand
 */
class CoreFilterDemand extends AbstractEntity
{

    /** @var int */
    protected $usage;

    /**
     * @return int
     */
    public function getUsage()
    {
        return $this->usage;
    }

    /**
     * @param int $usage
     */
    public function setUsage($usage)
    {
        $this->usage = $usage;
    }
}
