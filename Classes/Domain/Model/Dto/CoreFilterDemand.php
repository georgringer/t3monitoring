<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\Domain\Model\Dto;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class CoreFilterDemand extends AbstractEntity
{
    protected int $usage = 0;

    public function getUsage(): int
    {
        return $this->usage;
    }

    public function setUsage(int $usage): void
    {
        $this->usage = $usage;
    }
}
