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
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Client extends AbstractEntity
{
    #[Validate(['validator' => 'NotEmpty'])]
    protected string $title = '';

    #[Validate(['validator' => 'NotEmpty'])]
    protected string $domain = '';
    protected string $comment = '';
    protected string $hostHeader = '';
    protected bool $ignoreCertErrors = false;
    protected bool $excludeFromImport = false;
    protected string $forceIpResolve = '';
    protected string $basicAuthUsername = '';
    protected string $basicAuthPassword = '';

    #[Validate(['validator' => 'NotEmpty'])]
    protected string $secret = '';
    protected string $email = '';
    protected string $phpVersion = '';
    protected string $mysqlVersion = '';
    protected int $diskTotalSpace = 0;
    protected int $diskFreeSpace = 0;
    protected bool $insecureCore = false;
    protected bool $outdatedCore = false;
    protected int $insecureExtensions = 0;
    protected int $outdatedExtensions = 0;
    protected string $errorMessage = '';
    protected string $extraInfo = '';
    protected string $extraWarning = '';
    protected string $extraDanger = '';
    protected ?DateTime $lastSuccessfulImport = null;

    /**
     * @var LazyLoadingProxy|ObjectStorage<Extension>
     */
    #[Lazy]
    protected ObjectStorage|LazyLoadingProxy $extensions;

    #[Lazy]
    protected Core|LazyLoadingProxy|null $core = null;

    #[Lazy]
    protected Sla|LazyLoadingProxy|null $sla = null;

    #[Lazy]
    protected Tag|LazyLoadingProxy|null $tag = null;

    public function __construct()
    {
        // Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects(): void
    {
        $this->extensions = new ObjectStorage();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): void
    {
        $this->domain = $domain;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function getBasicAuthUsername(): string
    {
        return $this->basicAuthUsername;
    }

    public function setBasicAuthUsername(string $basicAuthUsername): void
    {
        $this->basicAuthUsername = $basicAuthUsername;
    }

    public function getBasicAuthPassword(): string
    {
        return $this->basicAuthPassword;
    }

    public function setBasicAuthPassword(string $basicAuthPassword): void
    {
        $this->basicAuthPassword = $basicAuthPassword;
    }

    public function getHostHeader(): string
    {
        return $this->hostHeader;
    }

    public function setHostHeader(string $hostHeader): void
    {
        $this->hostHeader = $hostHeader;
    }

    public function isIgnoreCertErrors(): bool
    {
        return $this->ignoreCertErrors;
    }

    public function setIgnoreCertErrors(bool $ignoreCertErrors): void
    {
        $this->ignoreCertErrors = $ignoreCertErrors;
    }

    public function isExcludeFromImport(): bool
    {
        return $this->excludeFromImport;
    }

    public function setExcludeFromImport(bool $excludeFromImport): void
    {
        $this->excludeFromImport = $excludeFromImport;
    }

    public function getForceIpResolve(): string
    {
        return $this->forceIpResolve;
    }

    public function setForceIpResolve(string $forceIpResolve): void
    {
        $this->forceIpResolve = $forceIpResolve;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function setSecret(string $secret): void
    {
        $this->secret = $secret;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhpVersion(): string
    {
        return $this->phpVersion;
    }

    public function setPhpVersion(string $phpVersion): void
    {
        $this->phpVersion = $phpVersion;
    }

    public function getMysqlVersion(): string
    {
        return $this->mysqlVersion;
    }

    public function setMysqlVersion(string $mysqlVersion): void
    {
        $this->mysqlVersion = $mysqlVersion;
    }

    public function getDiskFreeSpace(): int
    {
        return $this->diskFreeSpace;
    }

    public function setDiskFreeSpace(int $diskFreeSpace): void
    {
        $this->diskFreeSpace = $diskFreeSpace;
    }

    public function getDiskTotalSpace(): int
    {
        return $this->diskTotalSpace;
    }

    public function setDiskTotalSpace(int $diskTotalSpace): void
    {
        $this->diskTotalSpace = $diskTotalSpace;
    }

    public function getInsecureCore(): bool
    {
        return $this->insecureCore;
    }

    public function setInsecureCore(bool $insecureCore): void
    {
        $this->insecureCore = $insecureCore;
    }

    public function isInsecureCore(): bool
    {
        return $this->insecureCore;
    }

    public function getOutdatedCore(): bool
    {
        return $this->outdatedCore;
    }

    public function setOutdatedCore(bool $outdatedCore): void
    {
        $this->outdatedCore = $outdatedCore;
    }

    public function isOutdatedCore(): bool
    {
        return $this->outdatedCore;
    }

    public function getInsecureExtensions(): int
    {
        return $this->insecureExtensions;
    }

    public function setInsecureExtensions(int $insecureExtensions): void
    {
        $this->insecureExtensions = $insecureExtensions;
    }

    public function getOutdatedExtensions(): int
    {
        return $this->outdatedExtensions;
    }

    public function setOutdatedExtensions(int $outdatedExtensions): void
    {
        $this->outdatedExtensions = $outdatedExtensions;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(string $errorMessage): void
    {
        $this->errorMessage = $errorMessage;
    }

    public function getExtraInfo(): string
    {
        return $this->extraInfo;
    }

    public function setExtraInfo(string $extraInfo): void
    {
        $this->extraInfo = $extraInfo;
    }

    public function getExtraWarning(): string
    {
        return $this->extraWarning;
    }

    public function setExtraWarning(string $extraWarning): void
    {
        $this->extraWarning = $extraWarning;
    }

    public function getExtraDanger(): string
    {
        return $this->extraDanger;
    }

    public function setExtraDanger(string $extraDanger): void
    {
        $this->extraDanger = $extraDanger;
    }

    public function getLastSuccessfulImport(): ?DateTime
    {
        return $this->lastSuccessfulImport;
    }

    public function setLastSuccessfulImport(DateTime $lastSuccessfulImport): void
    {
        $this->lastSuccessfulImport = $lastSuccessfulImport;
    }

    public function addExtension(Extension $extension): void
    {
        $this->extensions->attach($extension);
    }

    public function removeExtension(Extension $extensionToRemove): void
    {
        $this->extensions->detach($extensionToRemove);
    }

    /**
     * Returns the extensions
     *
     * @return ObjectStorage<Extension>
     */
    public function getExtensions(): ObjectStorage
    {
        return $this->extensions instanceof LazyLoadingProxy ? $this->extensions->_loadRealInstance() : $this->extensions;
    }

    /**
     * Sets the extensions
     *
     * @param ObjectStorage<Extension> $extensions
     * @return void
     */
    public function setExtensions(ObjectStorage $extensions): void
    {
        $this->extensions = $extensions;
    }

    public function getCore(): ?Core
    {
        return $this->core instanceof LazyLoadingProxy ? $this->core->_loadRealInstance() : $this->core;
    }

    public function setCore(Core $core): void
    {
        $this->core = $core;
    }

    public function getSla(): ?Sla
    {
        return $this->sla instanceof LazyLoadingProxy ? $this->sla->_loadRealInstance() : $this->sla;
    }

    public function setSla(Sla $sla): void
    {
        $this->sla = $sla;
    }

    public function getTag(): ?Tag
    {
        return $this->tag instanceof LazyLoadingProxy ? $this->tag->_loadRealInstance() : $this->tag;
    }

    public function setTag(Tag $tag): void
    {
        $this->tag = $tag;
    }

    public function getExtraInfoAsArray(): array
    {
        if (!empty($this->extraInfo)) {
            return json_decode($this->extraInfo, true);
        }
        return [];
    }

    public function getExtraWarningAsArray(): array
    {
        if (!empty($this->extraWarning)) {
            return json_decode($this->extraWarning, true);
        }
        return [];
    }

    public function getExtraDangerAsArray(): array
    {
        if (!empty($this->extraDanger)) {
            return json_decode($this->extraDanger, true);
        }
        return [];
    }
}
