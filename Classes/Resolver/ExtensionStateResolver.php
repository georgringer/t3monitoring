<?php
namespace T3Monitor\T3monitoring\Resolver;

class ExtensionStateResolver extends BaseResolver
{
    const TITLE = 'Extension state';

    public function isPresentOperator(): bool
    {
        return in_array($this->argument, $this->resolverData->getResponse()['extensions']);
    }

    public function isNotPresentOperator(): bool
    {
        return !in_array($this->argument, $this->resolverData->getResponse()['extensions']);
    }

    public function isLoadedOperator(): bool
    {
        return (bool)$this->resolverData->getResponse()['extensions'][$this->argument]['isLoaded'] === true;
    }

    public function isNotLoadedOperator(): bool
    {
        return (bool)$this->resolverData->getResponse()['extensions'][$this->argument]['isLoaded'] === false;
    }
}