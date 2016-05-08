<?php

namespace T3Monitor\T3monitoring\Tests\Unit\Domain\Model\Dto;

use T3Monitor\T3monitoring\Domain\Model\Dto\CoreFilterDemand;
use TYPO3\CMS\Core\Tests\UnitTestCase;

class CoreFilterDemandTest extends UnitTestCase
{

    /**
     * @var CoreFilterDemand
     */
    protected $instance;

    protected function setUp()
    {
        $this->instance = new CoreFilterDemand();
    }

    /**
     * @test
     */
    public function usageCanBeSet()
    {
        $subject = 123;
        $this->instance->setUsage($subject);
        $this->assertEquals($subject, $this->instance->getUsage());
    }
}
