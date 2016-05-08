<?php
namespace T3Monitor\T3monitoring\Domain\Model;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Client
 */
class Client extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * title
     *
     * @var string
     * @validate NotEmpty
     */
    protected $title = '';

    /**
     * domain
     *
     * @var string
     * @validate NotEmpty
     */
    protected $domain = '';

    /**
     * secret
     *
     * @var string
     * @validate NotEmpty
     */
    protected $secret = '';

    /**
     * email
     *
     * @var string
     */
    protected $email = '';

    /**
     * phpVersion
     *
     * @var string
     */
    protected $phpVersion = '';

    /**
     * mysqlVersion
     *
     * @var string
     */
    protected $mysqlVersion = '';

    /**
     * insecureCore
     *
     * @var bool
     */
    protected $insecureCore = false;

    /**
     * outdatedCore
     *
     * @var bool
     */
    protected $outdatedCore = false;

    /**
     * insecureExtensions
     *
     * @var int
     */
    protected $insecureExtensions = 0;

    /**
     * outdatedExtensions
     *
     * @var int
     */
    protected $outdatedExtensions = 0;

    /**
     * errorMessage
     *
     * @var string
     */
    protected $errorMessage = '';

    /**
     * extraInfo
     *
     * @var string
     */
    protected $extraInfo = '';

    /**
     * extraWarning
     *
     * @var string
     */
    protected $extraWarning = '';

    /**
     * extraDanger
     *
     * @var string
     */
    protected $extraDanger = '';

    /**
     * lastSuccessfulImport
     *
     * @var \DateTime
     */
    protected $lastSuccessfulImport = null;

    /**
     * extensions
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3Monitor\T3monitoring\Domain\Model\Extension>
     * @lazy
     */
    protected $extensions = null;

    /**
     * core
     *
     * @var \T3Monitor\T3monitoring\Domain\Model\Core
     * @lazy
     */
    protected $core = null;

    /**
     * sla
     *
     * @var \T3Monitor\T3monitoring\Domain\Model\Sla
     * @lazy
     */
    protected $sla = null;

    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
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
        $this->extensions = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the title
     *
     * @return string $title
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
     * @return string $domain
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
     * Returns the secret
     *
     * @return string $secret
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
     * @return string $email
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
     * @return string $phpVersion
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
     * @return string $mysqlVersion
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
     * Returns the insecureCore
     *
     * @return bool $insecureCore
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
     * @return bool $outdatedCore
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
     * @return int $insecureExtensions
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
     * @return int $outdatedExtensions
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
     * @return string $errorMessage
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
     * @return string $extraInfo
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
     * @return string $extraWarning
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
     * @return string $extraDanger
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
     * @return \DateTime $lastSuccessfulImport
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
    public function addExtension(\T3Monitor\T3monitoring\Domain\Model\Extension $extension)
    {
        $this->extensions->attach($extension);
    }

    /**
     * Removes a Extension
     *
     * @param \T3Monitor\T3monitoring\Domain\Model\Extension $extensionToRemove The Extension to be removed
     * @return void
     */
    public function removeExtension(\T3Monitor\T3monitoring\Domain\Model\Extension $extensionToRemove)
    {
        $this->extensions->detach($extensionToRemove);
    }

    /**
     * Returns the extensions
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3Monitor\T3monitoring\Domain\Model\Extension> $extensions
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
    public function setExtensions(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $extensions)
    {
        $this->extensions = $extensions;
    }

    /**
     * Returns the core
     *
     * @return \T3Monitor\T3monitoring\Domain\Model\Core $core
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
    public function setCore(\T3Monitor\T3monitoring\Domain\Model\Core $core)
    {
        $this->core = $core;
    }

    /**
     * Returns the sla
     *
     * @return \T3Monitor\T3monitoring\Domain\Model\Sla $sla
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
    public function setSla(\T3Monitor\T3monitoring\Domain\Model\Sla $sla)
    {
        $this->sla = $sla;
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
