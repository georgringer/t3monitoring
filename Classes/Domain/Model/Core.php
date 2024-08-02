<?php
declare(strict_types=1);

namespace T3Monitor\T3monitoring\Domain\Model;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DateTime;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Core extends AbstractEntity
{
    protected string $version = '';
    protected bool $insecure = false;
    protected string $nextSecureVersion = '';
    protected int $type = 0;
    protected ?DateTime $releaseDate = null;
    protected string $latest = '';
    protected string $stable = '';
    protected bool $isStable = false;
    protected bool $isActive = false;
    protected bool $isLatest = false;
    protected int $versionInteger = 0;
    protected bool $isUsed = false;
    protected bool $isOfficial = false;

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    public function getInsecure(): bool
    {
        return $this->insecure;
    }

    public function setInsecure(bool $insecure): void
    {
        $this->insecure = $insecure;
    }

    public function isInsecure(): bool
    {
        return $this->insecure;
    }

    public function getNextSecureVersion(): string
    {
        return $this->nextSecureVersion;
    }

    public function setNextSecureVersion(string $nextSecureVersion): void
    {
        $this->nextSecureVersion = $nextSecureVersion;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getReleaseDate(): ?DateTime
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(DateTime $releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }

    public function getLatest(): string
    {
        return $this->latest;
    }

    public function setLatest(string $latest): void
    {
        $this->latest = $latest;
    }

    public function getStable(): string
    {
        return $this->stable;
    }

    public function setStable(string $stable): void
    {
        $this->stable = $stable;
    }

    public function getIsStable(): bool
    {
        return $this->isStable;
    }

    public function setIsStable(bool $isStable): void
    {
        $this->isStable = $isStable;
    }

    public function isIsStable(): bool
    {
        return $this->isStable;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function isIsActive(): bool
    {
        return $this->isActive;
    }

    public function getIsLatest(): bool
    {
        return $this->isLatest;
    }

    public function setIsLatest(bool $isLatest): void
    {
        $this->isLatest = $isLatest;
    }

    public function isIsLatest(): bool
    {
        return $this->isLatest;
    }

    public function getVersionInteger(): int
    {
        return $this->versionInteger;
    }

    public function setVersionInteger(int $versionInteger): void
    {
        $this->versionInteger = $versionInteger;
    }

    public function getIsUsed(): bool
    {
        return $this->isUsed;
    }

    public function setIsUsed(bool $isUsed): void
    {
        $this->isUsed = $isUsed;
    }

    public function isIsUsed(): bool
    {
        return $this->isUsed;
    }

    public function getIsOfficial(): bool
    {
        return $this->isOfficial;
    }

    public function setIsOfficial(bool $isOfficial): void
    {
        $this->isOfficial = $isOfficial;
    }

    public function isIsOfficial(): bool
    {
        return $this->isOfficial;
    }
}
