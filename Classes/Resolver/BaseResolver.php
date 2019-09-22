<?php
namespace T3Monitor\T3monitoring\Resolver;

use T3Monitor\T3monitoring\Domain\Model\Check;

class BaseResolver implements ResolverInterface
{
    /**
     * @var string
     */
    protected $argument;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var mixed
     */
    protected $valueForComparison;

    public function __construct(Check $check)
    {
        $this->argument = $check->getArgument();
        $this->operator = $check->getOperator();
        $this->value = $check->getValue();
    }

    public function setClientResponse(array $json) {
        $this->json = $json;
    }

    public function setValueForComparison() {
    }

    /**
     * Return value may be
     *
     * true  = Check failed
     * false = Check okay
     * null  = Missing provider data
     *
     * @return bool|null
     */
    public function execute()
    {
        $result = call_user_func_array([$this, $this->operator . 'Operator'], []);
        return $result;
    }

    public function getProviderArguments()
    {
    }
}