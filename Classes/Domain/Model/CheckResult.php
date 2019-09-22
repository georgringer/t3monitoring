<?php
namespace T3Monitor\T3monitoring\Domain\Model;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class CheckResult extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * @var \T3Monitor\T3monitoring\Domain\Model\Client
     */
    protected $client;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3Monitor\T3monitoring\Domain\Model\Rule>
     */
    protected $failedRules;

    /**
     * @var bool
     */
    protected $missingProviderData;

    public function __construct()
    {
        $this->failedRules = GeneralUtility::makeInstance(ObjectStorage::class);
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param int $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3Monitor\T3monitoring\Domain\Model\Rule> failedRules
     */
    public function getFailedRules()
    {
        return $this->failedRules;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3Monitor\T3monitoring\Domain\Model\Rule> $failedRules
     * @return void
     */
    public function setFailedRules(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $failedRules)
    {
        $this->failedRules = $failedRules;
    }

    /**
     * @param \T3Monitor\T3monitoring\Domain\Model\Rule
     */
    public function addFailedRule(Rule $failedRule)
    {
        $this->failedRules->attach($failedRule);
    }

    /**
     * @return bool
     */
    public function getMissingProviderData()
    {
        return $this->missingProviderData;
    }

    /**
     * @param bool $missingProviderData
     */
    public function setMissingProviderData(bool $missingProviderData)
    {
        $this->missingProviderData = $missingProviderData;
    }


}