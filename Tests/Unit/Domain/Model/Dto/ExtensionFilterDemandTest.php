<?php
namespace T3Monitor\T3monitoring\Tests\Unit\Domain\Model\Dto;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Dto\ExtensionFilterDemand;
use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * Class ExtensionFilterDemandTest
 */
class ExtensionFilterDemandTest extends UnitTestCase
{

    /**
     * @var ExtensionFilterDemand
     */
    protected $instance;

    /**
     * Set up
     */
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
