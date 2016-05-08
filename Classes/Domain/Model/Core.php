<?php
namespace T3Monitor\T3monitoring\Domain\Model;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Core
 */
class Core extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * version
     *
     * @var string
     */
    protected $version = '';

    /**
     * insecure
     *
     * @var bool
     */
    protected $insecure = false;

    /**
     * nextSecureVersion
     *
     * @var string
     */
    protected $nextSecureVersion = '';

    /**
     * type
     *
     * @var int
     */
    protected $type = 0;

    /**
     * releaseDate
     *
     * @var \DateTime
     */
    protected $releaseDate = null;

    /**
     * latest
     *
     * @var string
     */
    protected $latest = '';

    /**
     * stable
     *
     * @var string
     */
    protected $stable = '';

    /**
     * isStable
     *
     * @var bool
     */
    protected $isStable = false;

    /**
     * isActive
     *
     * @var bool
     */
    protected $isActive = false;

    /**
     * isLatest
     *
     * @var bool
     */
    protected $isLatest = false;

    /**
     * versionInteger
     *
     * @var int
     */
    protected $versionInteger = 0;

    /**
     * isUsed
     *
     * @var bool
     */
    protected $isUsed = false;

    /**
     * isOfficial
     *
     * @var bool
     */
    protected $isOfficial = false;

    /**
     * Returns the version
     *
     * @return string $version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Sets the version
     *
     * @param string $version
     * @return void
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * Returns the insecure
     *
     * @return bool $insecure
     */
    public function getInsecure()
    {
        return $this->insecure;
    }

    /**
     * Sets the insecure
     *
     * @param bool $insecure
     * @return void
     */
    public function setInsecure($insecure)
    {
        $this->insecure = $insecure;
    }

    /**
     * Returns the boolean state of insecure
     *
     * @return bool
     */
    public function isInsecure()
    {
        return $this->insecure;
    }

    /**
     * Returns the nextSecureVersion
     *
     * @return string $nextSecureVersion
     */
    public function getNextSecureVersion()
    {
        return $this->nextSecureVersion;
    }

    /**
     * Sets the nextSecureVersion
     *
     * @param string $nextSecureVersion
     * @return void
     */
    public function setNextSecureVersion($nextSecureVersion)
    {
        $this->nextSecureVersion = $nextSecureVersion;
    }

    /**
     * Returns the type
     *
     * @return int $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type
     *
     * @param int $type
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Returns the releaseDate
     *
     * @return \DateTime $releaseDate
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    /**
     * Sets the releaseDate
     *
     * @param \DateTime $releaseDate
     * @return void
     */
    public function setReleaseDate(\DateTime $releaseDate)
    {
        $this->releaseDate = $releaseDate;
    }

    /**
     * Returns the latest
     *
     * @return string $latest
     */
    public function getLatest()
    {
        return $this->latest;
    }

    /**
     * Sets the latest
     *
     * @param string $latest
     * @return void
     */
    public function setLatest($latest)
    {
        $this->latest = $latest;
    }

    /**
     * Returns the stable
     *
     * @return string $stable
     */
    public function getStable()
    {
        return $this->stable;
    }

    /**
     * Sets the stable
     *
     * @param string $stable
     * @return void
     */
    public function setStable($stable)
    {
        $this->stable = $stable;
    }

    /**
     * Returns the isStable
     *
     * @return bool $isStable
     */
    public function getIsStable()
    {
        return $this->isStable;
    }

    /**
     * Sets the isStable
     *
     * @param bool $isStable
     * @return void
     */
    public function setIsStable($isStable)
    {
        $this->isStable = $isStable;
    }

    /**
     * Returns the boolean state of isStable
     *
     * @return bool
     */
    public function isIsStable()
    {
        return $this->isStable;
    }

    /**
     * Returns the isActive
     *
     * @return bool $isActive
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Sets the isActive
     *
     * @param bool $isActive
     * @return void
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * Returns the boolean state of isActive
     *
     * @return bool
     */
    public function isIsActive()
    {
        return $this->isActive;
    }

    /**
     * Returns the isLatest
     *
     * @return bool $isLatest
     */
    public function getIsLatest()
    {
        return $this->isLatest;
    }

    /**
     * Sets the isLatest
     *
     * @param bool $isLatest
     * @return void
     */
    public function setIsLatest($isLatest)
    {
        $this->isLatest = $isLatest;
    }

    /**
     * Returns the boolean state of isLatest
     *
     * @return bool
     */
    public function isIsLatest()
    {
        return $this->isLatest;
    }

    /**
     * Returns the versionInteger
     *
     * @return int $versionInteger
     */
    public function getVersionInteger()
    {
        return $this->versionInteger;
    }

    /**
     * Sets the versionInteger
     *
     * @param int $versionInteger
     * @return void
     */
    public function setVersionInteger($versionInteger)
    {
        $this->versionInteger = $versionInteger;
    }

    /**
     * Returns the isUsed
     *
     * @return bool $isUsed
     */
    public function getIsUsed()
    {
        return $this->isUsed;
    }

    /**
     * Sets the isUsed
     *
     * @param bool $isUsed
     * @return void
     */
    public function setIsUsed($isUsed)
    {
        $this->isUsed = $isUsed;
    }

    /**
     * Returns the boolean state of isUsed
     *
     * @return bool
     */
    public function isIsUsed()
    {
        return $this->isUsed;
    }

    /**
     * Returns the isOfficial
     *
     * @return bool $isOfficial
     */
    public function getIsOfficial()
    {
        return $this->isOfficial;
    }

    /**
     * Sets the isOfficial
     *
     * @param bool $isOfficial
     * @return void
     */
    public function setIsOfficial($isOfficial)
    {
        $this->isOfficial = $isOfficial;
    }

    /**
     * Returns the boolean state of isOfficial
     *
     * @return bool
     */
    public function isIsOfficial()
    {
        return $this->isOfficial;
    }
}
