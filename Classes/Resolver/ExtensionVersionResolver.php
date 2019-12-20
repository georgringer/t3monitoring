<?php
namespace T3Monitor\T3monitoring\Resolver;

class ExtensionVersionResolver extends ComposerVersionResolver
{
    const TITLE = 'Extension version';

    public function setValueForComparison()
    {
        $this->valueForComparison = $this->resolverData->getResponse()['extensions'][$this->argument]['version'];
    }
}
