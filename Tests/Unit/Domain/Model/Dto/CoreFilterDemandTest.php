<?php
namespace T3Monitor\T3monitoring\Tests\Unit\Domain\Model\Dto;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Dto\CoreFilterDemand;
use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * Class CoreFilterDemandTest
 */
class CoreFilterDemandTest extends UnitTestCase
{

    /**
     * @var CoreFilterDemand
     */
    protected $instance;

    /**
     * Set up
     */
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
