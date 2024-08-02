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
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Extension extends AbstractEntity
{
    public static array $defaultStates = [
        0 => 'alpha',
        1 => 'beta',
        2 => 'stable',
        3 => 'experimental',
        4 => 'test',
        5 => 'obsolete',
        6 => 'excludeFromUpdates',
        999 => 'n/a'
    ];

    public static array $defaultCategories = [
        0 => 'be',
        1 => 'module',
        2 => 'fe',
        3 => 'plugin',
        4 => 'misc',
        5 => 'services',
        6 => 'templates',
        8 => 'doc',
        9 => 'example',
        10 => 'distribution'
    ];

    #[Validate(['validator' => 'NotEmpty'])]
    protected string $name = '';
    protected string $version = '';
    protected bool $insecure = false;
    protected string $nextSecureVersion = '';
    protected string $title = '';
    protected string $description = '';
    protected ?DateTime $lastUpdated = null;
    protected string $authorName = '';
    protected string $updateComment = '';
    protected int $state = 0;
    protected int $category = 0;
    protected int $versionInteger = 0;
    protected bool $isUsed = false;
    protected bool $isOfficial = false;
    protected bool $isModified = false;
    protected bool $isLatest = false;
    protected string $lastBugfixRelease = '';
    protected string $lastMinorRelease = '';
    protected string $lastMajorRelease = '';
    protected string $serializedDependencies = '';
    protected int $typo3MinVersion = 0;
    protected int $typo3MaxVersion = 0;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

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

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getLastUpdated(): ?DateTime
    {
        return $this->lastUpdated;
    }

    public function setLastUpdated(DateTime $lastUpdated): void
    {
        $this->lastUpdated = $lastUpdated;
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    public function setAuthorName(string $authorName): void
    {
        $this->authorName = $authorName;
    }

    public function getUpdateComment(): string
    {
        return $this->updateComment;
    }

    public function setUpdateComment(string $updateComment): void
    {
        $this->updateComment = $updateComment;
    }

    public function getState(): int
    {
        return $this->state;
    }

    public function setState(int $state): void
    {
        $this->state = $state;
    }

    public function getCategory(): int
    {
        return $this->category;
    }

    public function setCategory(int $category): void
    {
        $this->category = $category;
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

    public function getIsModified(): bool
    {
        return $this->isModified;
    }

    public function setIsModified(bool $isModified): void
    {
        $this->isModified = $isModified;
    }

    public function isIsModified(): bool
    {
        return $this->isModified;
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

    public function getLastBugfixRelease(): string
    {
        return $this->lastBugfixRelease;
    }

    public function setLastBugfixRelease(string $lastBugfixRelease): void
    {
        $this->lastBugfixRelease = $lastBugfixRelease;
    }

    public function getLastMinorRelease(): string
    {
        return $this->lastMinorRelease;
    }

    public function setLastMinorRelease(string $lastMinorRelease): void
    {
        $this->lastMinorRelease = $lastMinorRelease;
    }

    public function getLastMajorRelease(): string
    {
        return $this->lastMajorRelease;
    }

    public function setLastMajorRelease(string $lastMajorRelease): void
    {
        $this->lastMajorRelease = $lastMajorRelease;
    }

    public function getSerializedDependencies(): string
    {
        return $this->serializedDependencies;
    }

    public function setSerializedDependencies(string $serializedDependencies): void
    {
        $this->serializedDependencies = $serializedDependencies;
    }

    public function getTypo3MinVersion(): int
    {
        return $this->typo3MinVersion;
    }

    public function setTypo3MinVersion(int $typo3MinVersion): void
    {
        $this->typo3MinVersion = $typo3MinVersion;
    }

    public function getTypo3MaxVersion(): int
    {
        return $this->typo3MaxVersion;
    }

    public function setTypo3MaxVersion(int $typo3MaxVersion): void
    {
        $this->typo3MaxVersion = $typo3MaxVersion;
    }
}
