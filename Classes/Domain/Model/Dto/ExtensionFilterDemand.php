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

class ExtensionFilterDemand extends AbstractEntity
{
    protected string $name = '';
    protected bool $exactSearch = false;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function isExactSearch(): bool
    {
        return $this->exactSearch;
    }

    public function setExactSearch(bool $exactSearch): void
    {
        $this->exactSearch = $exactSearch;
    }
}
