<?php
namespace T3Monitor\T3monitoring\Domain\Model;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Backend\User;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Client
 */
class Client extends AbstractEntity
{

    /**
     *
     * @var string
     * @validate NotEmpty
     */
    protected $title = '';

    /**
     * @var string
     * @validate NotEmpty
     */
    protected $domain = '';

    /**
     * @var string
     */
    protected $basicAuthUsername = '';

    /**
     * @var string
     */
    protected $basicAuthPassword = '';

    /**
     * @var string
     * @validate NotEmpty
     */
    protected $secret = '';

    /**
     * @var string
     */
    protected $email = '';

    /**
     * @var string
     */
    protected $phpVersion = '';

    /**
     * @var string
     */
    protected $mysqlVersion = '';

    /**
     * @var int
     */
    protected $diskTotalSpace = 0;

    /**
     * @var int
     */
    protected $diskFreeSpace = 0;

    /**
     * @var bool
     */
    protected $insecureCore = false;

    /**
     * @var bool
     */
    protected $outdatedCore = false;

    /**
     * @var int
     */
    protected $insecureExtensions = 0;

    /**
     * @var int
     */
    protected $outdatedExtensions = 0;

    /**
     * @var string
     */
    protected $errorMessage = '';

    /**
     * @var string
     */
    protected $extraInfo = '';

    /**
     * @var string
     */
    protected $extraWarning = '';

    /**
     * @var string
     */
    protected $extraDanger = '';

    /**
     * @var \DateTime
     */
    protected $lastSuccessfulImport = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3Monitor\T3monitoring\Domain\Model\Backend\User>
     * @lazy
     */
    protected $backendUsers = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3Monitor\T3monitoring\Domain\Model\Extension>
     * @lazy
     */
    protected $extensions = null;

    /**
     * @var \T3Monitor\T3monitoring\Domain\Model\Core
     * @lazy
     */
    protected $core = null;

    /**
     * @var \T3Monitor\T3monitoring\Domain\Model\Sla
     * @lazy
     */
    protected $sla = null;

    /**
     * @var \T3Monitor\T3monitoring\Domain\Model\Tag
     * @lazy
     */
    protected $tag = null;

    /**
     * __construct
     */
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
    protected function initStorageObjects()
    {
        $this->extensions = new ObjectStorage();
        $this->backendUsers = new ObjectStorage();
    }

    /**
     * Returns the title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Sets the domain
     *
     * @param string $domain
     * @return void
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getBasicAuthUsername()
    {
        return $this->basicAuthUsername;
    }

    /**
     * @param string $basicAuthUsername
     */
    public function setBasicAuthUsername($basicAuthUsername)
    {
        $this->basicAuthUsername = $basicAuthUsername;
    }

    /**
     * @return string
     */
    public function getBasicAuthPassword()
    {
        return $this->basicAuthPassword;
    }

    /**
     * @param string $basicAuthPassword
     */
    public function setBasicAuthPassword($basicAuthPassword)
    {
        $this->basicAuthPassword = $basicAuthPassword;
    }

    /**
     * Returns the secret
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Sets the secret
     *
     * @param string $secret
     * @return void
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * Returns the email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email
     *
     * @param string $email
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Returns the phpVersion
     *
     * @return string
     */
    public function getPhpVersion()
    {
        return $this->phpVersion;
    }

    /**
     * Sets the phpVersion
     *
     * @param string $phpVersion
     * @return void
     */
    public function setPhpVersion($phpVersion)
    {
        $this->phpVersion = $phpVersion;
    }

    /**
     * Returns the mysqlVersion
     *
     * @return string
     */
    public function getMysqlVersion()
    {
        return $this->mysqlVersion;
    }

    /**
     * Sets the mysqlVersion
     *
     * @param string $mysqlVersion
     * @return void
     */
    public function setMysqlVersion($mysqlVersion)
    {
        $this->mysqlVersion = $mysqlVersion;
    }

    /**
     * Returns the diskFreeSpace
     *
     * @return int
     */
    public function getDiskFreeSpace()
    {
        return $this->diskFreeSpace;
    }

    /**
     * Sets the diskFreeSpace
     *
     * @param int $diskFreeSpace
     * @return void
     */
    public function setDiskFreeSpace($diskFreeSpace)
    {
        $this->diskFreeSpace = $diskFreeSpace;
    }

    /**
     * Returns the diskTotalSpace
     *
     * @return int
     */
    public function getDiskTotalSpace()
    {
        return $this->diskTotalSpace;
    }

    /**
     * Sets the diskTotalSpace
     *
     * @param int $diskTotalSpace
     * @return void
     */
    public function setDiskTotalSpace($diskTotalSpace)
    {
        $this->diskTotalSpace = $diskTotalSpace;
    }

    /**
     * Returns the insecureCore
     *
     * @return bool
     */
    public function getInsecureCore()
    {
        return $this->insecureCore;
    }

    /**
     * Sets the insecureCore
     *
     * @param bool $insecureCore
     * @return void
     */
    public function setInsecureCore($insecureCore)
    {
        $this->insecureCore = $insecureCore;
    }

    /**
     * Returns the boolean state of insecureCore
     *
     * @return bool
     */
    public function isInsecureCore()
    {
        return $this->insecureCore;
    }

    /**
     * Returns the outdatedCore
     *
     * @return bool
     */
    public function getOutdatedCore()
    {
        return $this->outdatedCore;
    }

    /**
     * Sets the outdatedCore
     *
     * @param bool $outdatedCore
     * @return void
     */
    public function setOutdatedCore($outdatedCore)
    {
        $this->outdatedCore = $outdatedCore;
    }

    /**
     * Returns the boolean state of outdatedCore
     *
     * @return bool
     */
    public function isOutdatedCore()
    {
        return $this->outdatedCore;
    }

    /**
     * Returns the insecureExtensions
     *
     * @return int
     */
    public function getInsecureExtensions()
    {
        return $this->insecureExtensions;
    }

    /**
     * Sets the insecureExtensions
     *
     * @param int $insecureExtensions
     * @return void
     */
    public function setInsecureExtensions($insecureExtensions)
    {
        $this->insecureExtensions = $insecureExtensions;
    }

    /**
     * Returns the outdatedExtensions
     *
     * @return int
     */
    public function getOutdatedExtensions()
    {
        return $this->outdatedExtensions;
    }

    /**
     * Sets the outdatedExtensions
     *
     * @param int $outdatedExtensions
     * @return void
     */
    public function setOutdatedExtensions($outdatedExtensions)
    {
        $this->outdatedExtensions = $outdatedExtensions;
    }

    /**
     * Returns the errorMessage
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Sets the errorMessage
     *
     * @param string $errorMessage
     * @return void
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * Returns the extraInfo
     *
     * @return string
     */
    public function getExtraInfo()
    {
        return $this->extraInfo;
    }

    /**
     * Sets the extraInfo
     *
     * @param string $extraInfo
     * @return void
     */
    public function setExtraInfo($extraInfo)
    {
        $this->extraInfo = $extraInfo;
    }

    /**
     * Returns the extraWarning
     *
     * @return string
     */
    public function getExtraWarning()
    {
        return $this->extraWarning;
    }

    /**
     * Sets the extraWarning
     *
     * @param string $extraWarning
     * @return void
     */
    public function setExtraWarning($extraWarning)
    {
        $this->extraWarning = $extraWarning;
    }

    /**
     * Returns the extraDanger
     *
     * @return string
     */
    public function getExtraDanger()
    {
        return $this->extraDanger;
    }

    /**
     * Sets the extraDanger
     *
     * @param string $extraDanger
     * @return void
     */
    public function setExtraDanger($extraDanger)
    {
        $this->extraDanger = $extraDanger;
    }

    /**
     * Returns the lastSuccessfulImport
     *
     * @return \DateTime
     */
    public function getLastSuccessfulImport()
    {
        return $this->lastSuccessfulImport;
    }

    /**
     * Sets the lastSuccessfulImport
     *
     * @param \DateTime $lastSuccessfulImport
     * @return void
     */
    public function setLastSuccessfulImport(\DateTime $lastSuccessfulImport)
    {
        $this->lastSuccessfulImport = $lastSuccessfulImport;
    }

    /**
     * Adds a Extension
     *
     * @param \T3Monitor\T3monitoring\Domain\Model\Extension $extension
     * @return void
     */
    public function addExtension(Extension $extension)
    {
        $this->extensions->attach($extension);
    }

    /**
     * Removes a Extension
     *
     * @param \T3Monitor\T3monitoring\Domain\Model\Extension $extensionToRemove The Extension to be removed
     * @return void
     */
    public function removeExtension(Extension $extensionToRemove)
    {
        $this->extensions->detach($extensionToRemove);
    }

    /**
     * Returns the extensions
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3Monitor\T3monitoring\Domain\Model\Extension>
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * Sets the extensions
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3Monitor\T3monitoring\Domain\Model\Extension> $extensions
     * @return void
     */
    public function setExtensions(ObjectStorage $extensions)
    {
        $this->extensions = $extensions;
    }

    /**
     * Adds a backend user
     *
     * @param User $backendUser
     * @return void
     */
    public function addBackendUser(User $backendUser)
    {
        $this->backendUsers->attach($backendUser);
    }

    /**
     * Removes a backend user
     *
     * @param User $backendUserToRemove The backend user to be removed
     * @return void
     */
    public function removeBackendUser(User $backendUserToRemove)
    {
        $this->backendUsers->detach($backendUserToRemove);
    }

    /**
     * Returns the backend users
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3Monitor\T3monitoring\Domain\Model\Backend\User> $backendUsers
     */
    public function getBackendUsers()
    {
        return $this->backendUsers;
    }

    /**
     * Sets the backend users
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3Monitor\T3monitoring\Domain\Model\Backend\User> $backendUsers
     * @return void
     */
    public function setBackendUsers(ObjectStorage $backendUsers)
    {
        $this->backendUsers = $backendUsers;
    }

    /**
     * Returns the core
     *
     * @return \T3Monitor\T3monitoring\Domain\Model\Core
     */
    public function getCore()
    {
        return $this->core;
    }

    /**
     * Sets the core
     *
     * @param \T3Monitor\T3monitoring\Domain\Model\Core $core
     * @return void
     */
    public function setCore(Core $core)
    {
        $this->core = $core;
    }

    /**
     * Returns the sla
     *
     * @return \T3Monitor\T3monitoring\Domain\Model\Sla
     */
    public function getSla()
    {
        return $this->sla;
    }

    /**
     * Sets the sla
     *
     * @param \T3Monitor\T3monitoring\Domain\Model\Sla $sla
     * @return void
     */
    public function setSla(Sla $sla)
    {
        $this->sla = $sla;
    }

    /**
     * Returns the tag
     *
     * @return \T3Monitor\T3monitoring\Domain\Model\Tag
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Sets the tag
     *
     * @param \T3Monitor\T3monitoring\Domain\Model\Tag $tag
     * @return void
     */
    public function setTag(Tag $tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return array
     */
    public function getExtraInfoAsArray()
    {
        if (!empty($this->extraInfo)) {
            return json_decode($this->extraInfo, true);
        }
        return [];
    }

    /**
     * @return array
     */
    public function getExtraWarningAsArray()
    {
        if (!empty($this->extraWarning)) {
            return json_decode($this->extraWarning, true);
        }
        return [];
    }

    /**
     * @return array
     */
    public function getExtraDangerAsArray()
    {
        if (!empty($this->extraDanger)) {
            return json_decode($this->extraDanger, true);
        }
        return [];
    }
}
