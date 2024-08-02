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

class ClientFilterDemand extends AbstractEntity
{
    protected string $version = '';
    protected int $sla = 0;
    protected int $tag = 0;
    protected string $searchWord = '';
    protected bool $withErrorMessage = false;
    protected bool $withInsecureExtensions = false;
    protected bool $withInsecureCore = false;
    protected bool $withOutdatedCore = false;
    protected bool $withOutdatedExtensions = false;
    protected bool $withExtraInfo = false;
    protected bool $withExtraWarning = false;
    protected bool $withExtraDanger = false;
    protected bool $withEmailAddress = false;

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): static
    {
        $this->version = $version;
        return $this;
    }

    public function getSla(): int
    {
        return $this->sla;
    }

    public function setSla(int $sla): static
    {
        $this->sla = $sla;
        return $this;
    }

    public function getTag(): int
    {
        return $this->tag;
    }

    public function setTag(int $tag): static
    {
        $this->tag = $tag;
        return $this;
    }

    public function getSearchWord(): string
    {
        return $this->searchWord;
    }

    public function setSearchWord(string $searchWord): static
    {
        $this->searchWord = $searchWord;
        return $this;
    }

    public function isWithErrorMessage(): bool
    {
        return $this->withErrorMessage;
    }

    public function setWithErrorMessage(bool $withErrorMessage): static
    {
        $this->withErrorMessage = $withErrorMessage;
        return $this;
    }

    public function isWithInsecureExtensions(): bool
    {
        return $this->withInsecureExtensions;
    }

    public function setWithInsecureExtensions(bool $withInsecureExtensions): static
    {
        $this->withInsecureExtensions = $withInsecureExtensions;
        return $this;
    }

    public function isWithInsecureCore(): bool
    {
        return $this->withInsecureCore;
    }

    public function setWithInsecureCore(bool $withInsecureCore): static
    {
        $this->withInsecureCore = $withInsecureCore;
        return $this;
    }

    public function isWithOutdatedCore(): bool
    {
        return $this->withOutdatedCore;
    }

    public function setWithOutdatedCore(bool $withOutdatedCore): static
    {
        $this->withOutdatedCore = $withOutdatedCore;
        return $this;
    }

    public function isWithOutdatedExtensions(): bool
    {
        return $this->withOutdatedExtensions;
    }

    public function setWithOutdatedExtensions(bool $withOutdatedExtensions): static
    {
        $this->withOutdatedExtensions = $withOutdatedExtensions;
        return $this;
    }

    public function isWithExtraInfo(): bool
    {
        return $this->withExtraInfo;
    }

    public function setWithExtraInfo(bool $withExtraInfo): static
    {
        $this->withExtraInfo = $withExtraInfo;
        return $this;
    }

    public function isWithExtraWarning(): bool
    {
        return $this->withExtraWarning;
    }

    public function setWithExtraWarning(bool $withExtraWarning): static
    {
        $this->withExtraWarning = $withExtraWarning;
        return $this;
    }

    public function isWithExtraDanger(): bool
    {
        return $this->withExtraDanger;
    }

    public function setWithExtraDanger(bool $withExtraDanger): static
    {
        $this->withExtraDanger = $withExtraDanger;
        return $this;
    }

    public function isWithEmailAddress(): bool
    {
        return $this->withEmailAddress;
    }

    public function setWithEmailAddress(bool $withEmailAddress): static
    {
        $this->withEmailAddress = $withEmailAddress;
        return $this;
    }
}
