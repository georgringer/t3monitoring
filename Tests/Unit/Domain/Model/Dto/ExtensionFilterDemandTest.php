<?php

namespace T3Monitor\T3monitoring\Tests\Unit\Domain\Model\Dto;

use T3Monitor\T3monitoring\Domain\Model\Dto\ExtensionFilterDemand;
use TYPO3\CMS\Core\Tests\UnitTestCase;

class ExtensionFilterDemandTest extends UnitTestCase
{

    /**
     * @var ExtensionFilterDemand
     */
    protected $instance;

    protected function setUp()
    {
        $this->instance = new ExtensionFilterDemand();
    }

    /**
     * @test
     */
    public function nameCanBeSet()
    {
        $subject = 'MyExt';
        $this->instance->setName($subject);
        $this->assertEquals($subject, $this->instance->getName());
    }

    /**
     * @test
     */
    public function exactSearchCanBeSet()
    {
        $subject = true;
        $this->instance->setExactSearch($subject);
        $this->assertEquals($subject, $this->instance->isExactSearch());
    }
}
