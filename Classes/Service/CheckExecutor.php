<?php
namespace T3Monitor\T3monitoring\Service;

use T3Monitor\T3monitoring\Domain\Model\Check;
use T3Monitor\T3monitoring\Domain\Repository\RuleRepository;
use T3Monitor\T3monitoring\Resolver\ResolverInterface;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class CheckExecutor implements SingletonInterface
{
    /** @var QueryResultInterface */
    protected $rules;

    /** @var array */
    protected $providerArguments;

    /** @var array */
    protected $json;

    public function __construct(RuleRepository $ruleRepository)
    {
        $this->rules = $ruleRepository->findAll();
    }

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
                $allProviderArguments[$check->getType()][] = $providerArguments;
            }
        }

        $this->providerArguments = $allProviderArguments;
        return $allProviderArguments;
    }

    public function applyRulesAndModifyClientData(&$json)
    {
        $this->json = $json;
        foreach ($this->rules as $rule) {
            $executionCriterias = $rule->getExecutionCriteria();
            if (count($executionCriterias) === 0 || $this->checkCriterias($executionCriterias) === true) {
                if ($this->checkCriterias($rule->getFailureCriteria()) === true) {
                    $json['extra'][$rule->getMessageCategory()][$rule->getTitle()] = $rule->getDescription();
                }
            }
        }
    }

    protected function checkCriterias($criterias): bool
    {
        $result = false;
        if ($criterias) {
            foreach ($criterias as $criteria) {
                if ($result === true) {
                    break;
                }
                $result = $this->runCheck($criteria);
            }
        }
        return $result;
    }

    protected function runCheck(Check $check): bool
    {
        $resolver = $this->getResolver($check);
        $resolver->setClientResponse($this->json);
        $resolver->setValueForComparison();
        return $resolver->execute();
    }

    protected function getResolver(Check $check): ResolverInterface
    {
        $resolverClass = $GLOBALS['TYPO3_CONF_VARS']['EXT']['t3monitoring']['resolver'][$check->getType()];
        $resolver = GeneralUtility::makeInstance($resolverClass, $check);
        return $resolver;
    }
}