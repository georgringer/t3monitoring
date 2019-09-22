<?php
namespace T3Monitor\T3monitoring\Resolver;

use T3Monitor\T3monitoring\Domain\Model\Dto\ResolverData;

class HeaderResolver extends BaseResolver
{
    const TITLE = 'HTTP Header';

    public function setValueForComparison()
    {
        $this->valueForComparison = $this->resolverData->getResponseHeaders()[$this->argument];
    }

    public function isSetOperator(): bool
    {
        return isset($this->valueForComparison);
    }

    public function isNotSetOperator(): bool
    {
        return !isset($this->valueForComparison);
    }
}