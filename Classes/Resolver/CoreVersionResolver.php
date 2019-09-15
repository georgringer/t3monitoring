<?php
namespace T3Monitor\T3monitoring\Resolver;

class CoreVersionResolver extends ComposerVersionResolver
{
    const TITLE = 'Core version';

    public function setValueForComparison()
    {
        $this->valueForComparison = $this->json['core']['typo3Version'];
    }
}