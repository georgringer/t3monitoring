<?php
namespace T3Monitor\T3monitoring\Resolver;

class ConfigurationResolver extends BaseResolver
{
    const TITLE = 'Configuration value';

    public function setValueForComparison()
    {
        if (isset($this->json['configuration'][$this->argument])) {
            $this->valueForComparison = $this->json['configuration'][$this->argument];
        }
    }

    public function getProviderArguments()
    {
        return $this->argument;
    }

    public function isFalseOperator(): bool
    {
        return (bool)$this->valueForComparison === false;
    }

    public function isTrueOperator(): bool
    {
        return (bool)$this->valueForComparison === true;
    }

    public function isOperator(): bool
    {
        return $this->valueForComparison === $this->value;
    }

    public function isNotOperator(): bool
    {
        return $this->valueForComparison !== $this->value;
    }
}