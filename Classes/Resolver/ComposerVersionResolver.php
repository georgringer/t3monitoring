<?php
namespace T3Monitor\T3monitoring\Resolver;

use Composer\Semver\Semver;

class ComposerVersionResolver extends BaseResolver
{
    public function matchesVersionOperator()
    {
        return Semver::satisfies($this->valueForComparison, $this->value);
    }

    public function matchesNotVersionOperator()
    {
        return !Semver::satisfies($this->valueForComparison, $this->value);
    }
}