<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\Tests\Unit\Domain\Model\Dto;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use PHPUnit\Framework\Attributes\Test;
use T3Monitor\T3monitoring\Domain\Model\Dto\CoreFilterDemand;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class CoreFilterDemandTest extends UnitTestCase
{
    protected CoreFilterDemand $instance;

    /**
     * Set up
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->instance = new CoreFilterDemand();
    }

    #[Test]
    public function usageCanBeSet()
    {
        $subject = 123;
        $this->instance->setUsage($subject);
        $this->assertEquals($subject, $this->instance->getUsage());
    }
}
