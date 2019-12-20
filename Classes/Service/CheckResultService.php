<?php
namespace T3Monitor\T3monitoring\Service;

use T3Monitor\T3monitoring\Domain\Model\Check;
use T3Monitor\T3monitoring\Domain\Model\CheckResult;
use T3Monitor\T3monitoring\Domain\Model\Dto\ResolverData;
use T3Monitor\T3monitoring\Domain\Repository\CheckResultRepository;
use T3Monitor\T3monitoring\Domain\Repository\RuleRepository;
use T3Monitor\T3monitoring\Resolver\ResolverInterface;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class CheckResultService implements SingletonInterface
{
    /** @var ResolverData */
    protected $resolverData;

    /** @var QueryResultInterface */
    protected $rules;

    /** @var CheckResultRepository */
    protected $checkResultRepository;

    /** @var PersistenceManager */
    protected $persistenceManager;

    /** @var array */
    protected $providerArguments;

    public function __construct(
        RuleRepository $ruleRepository,
        CheckResultRepository $checkResultRepository,
        PersistenceManager $persistenceManager
    ) {
        $this->rules = $ruleRepository->findAll();
        $this->checkResultRepository = $checkResultRepository;
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * @return array
     */
    public function getProviderArguments()
    {
        if ($this->providerArguments) {
            return $this->providerArguments;
        }

        $allProviderArguments = [];
        $checks = [];
        foreach ($this->rules as $rule) {
            foreach ($rule->getFailureCriteria() as $check) {
                $checks[$check->getUid()] = $check;
            }
            foreach ($rule->getExecutionCriteria() as $check) {
                $checks[$check->getUid()] = $check;
            }
        }
        foreach ($checks as $check) {
            $providerArguments = $this->getResolver($check)->getProviderArguments();
            if ($providerArguments) {
                if (is_array($providerArguments)) {
                    $allProviderArguments[$check->getType()] = $providerArguments;
                } else {
                    $allProviderArguments[$check->getType()][] = $providerArguments;
                }
            }
        }

        $this->providerArguments = $allProviderArguments;
        return $allProviderArguments;
    }

    /**
     * @param ResolverData $resolverData
     * @return CheckResult
     * @throws IllegalObjectTypeException
     */
    public function createCheckResult(ResolverData $resolverData)
    {
        $this->resolverData = $resolverData;
        $checkResult = new CheckResult();
        $checkResult->setClient($resolverData->getClient()['uid']);

        foreach ($this->rules as $rule) {
            $this->resolverData->setRule($rule);
            $checkFailureCriterias = true;
            foreach ($rule->getExecutionCriteria() as $executionCriteria) {
                $checkFailureCriterias = $this->runCheck($executionCriteria);
                if (is_null($checkFailureCriterias)) {
                    $checkResult->setMissingProviderData(true);
                    break;
                } elseif ($checkFailureCriterias === false) {
                    break;
                }
            }
            if ($checkFailureCriterias === true) {
                foreach ($rule->getFailureCriteria() as $failureCritera) {
                    $result = $this->runCheck($failureCritera);
                    if ($result === true) {
                        $checkResult->addFailedRule($rule);
                        break;
                    } elseif (is_null($result)) {
                        $checkResult->setMissingProviderData(true);
                        break;
                    }
                }
            }
        }

        $this->checkResultRepository->add($checkResult);
        $this->persistenceManager->persistAll();

        return $checkResult;
    }

    /**
     * @param Check $check
     * @return null|bool
     */
    protected function runCheck(Check $check)
    {
        $resolver = $this->getResolver($check);
        $resolver->setup($this->resolverData);
        $resolver->setValueForComparison();
        return $resolver->execute();
    }

    /**
     * @param Check $check
     * @return ResolverInterface
     */
    protected function getResolver(Check $check)
    {
        $resolverClass = $GLOBALS['TYPO3_CONF_VARS']['EXT']['t3monitoring']['resolver'][$check->getType()];
        $resolver = GeneralUtility::makeInstance($resolverClass, $check);
        return $resolver;
    }
}
