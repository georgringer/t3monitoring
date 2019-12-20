<?php
namespace T3Monitor\T3monitoring\Resolver;

use T3Monitor\T3monitoring\Domain\Model\Dto\ResolverData;

interface ResolverInterface
{
    public function setup(ResolverData $resolverData);

    public function setValueForComparison();

    public function execute();

    public function getProviderArguments();
}
