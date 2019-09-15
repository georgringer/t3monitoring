<?php
namespace T3Monitor\T3monitoring\Resolver;

class ExtensionVersionResolver extends ComposerVersionResolver
{
    const TITLE = 'Extension version';

    public function setup() {
        $this->valueForComparison = $this->json['extensions'][$this->argument]['version'];
    }
}