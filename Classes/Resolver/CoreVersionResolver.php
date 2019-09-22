<?php
namespace T3Monitor\T3monitoring\Resolver;

class CoreVersionResolver extends ComposerVersionResolver
{
    const TITLE = 'Core version';

    public function setValueForComparison()
    {
        $this->valueForComparison = $this->resolverData->getResponse()['core']['typo3Version'];
    }
}