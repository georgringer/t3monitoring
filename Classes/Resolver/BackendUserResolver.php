<?php
namespace T3Monitor\T3monitoring\Resolver;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class BackendUserResolver extends BaseResolver
{
    const TITLE = 'Backend user';

    public function setValueForComparison()
    {
        $this->valueForComparison = $this->resolverData->getResponse()['backendUser'];
    }

    public function getProviderArguments()
    {
        return GeneralUtility::trimExplode(PHP_EOL, $this->argument, true);
    }

    protected function isActiveOperator()
    {
        if (!isset($this->resolverData->getResponse()['backendUser'])) {
            return;
        }

        $unwantedActiveUsers = array_intersect($this->getProviderArguments(),
            $this->resolverData->getResponse()['backendUser']);

        if (count($unwantedActiveUsers) > 0) {
            $this->addRuleData($unwantedActiveUsers);
        }
        return $unwantedActiveUsers !== 0;
    }

    protected function addRuleData($backendUsers)
    {
        $update = $this->resolverData->getResponse();
        $messageCategory = $this->resolverData->getRule()->getMessageCategory();
        $update['extra'][$messageCategory][$this->resolverData->getRule()->getTitle()] = implode(',',
            $backendUsers);
        $this->resolverData->setResponse($update);
    }
}
