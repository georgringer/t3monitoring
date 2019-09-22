<?php
namespace T3Monitor\T3monitoring\Resolver;

interface ResolverInterface
{
    public function setClientResponse(array $json);

    public function setValueForComparison();

    public function execute();

    public function getProviderArguments();
}