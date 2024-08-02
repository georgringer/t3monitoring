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
use T3Monitor\T3monitoring\Domain\Model\Dto\ExtensionFilterDemand;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ExtensionFilterDemandTest extends UnitTestCase
{
    protected ExtensionFilterDemand $instance;

    protected function setUp(): void
    {
        parent::setUp();
        $this->instance = new ExtensionFilterDemand();
    }

    #[Test]
    public function nameCanBeSet()
    {
        $subject = 'MyExt';
        $this->instance->setName($subject);
        $this->assertEquals($subject, $this->instance->getName());
    }

    #[Test]
    public function exactSearchCanBeSet()
    {
        $this->instance->setExactSearch(true);
        $this->assertTrue($this->instance->isExactSearch());
    }
}
