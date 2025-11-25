<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\Tests\Unit\Domain\Model;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use PHPUnit\Framework\Attributes\Test;
use T3Monitor\T3monitoring\Domain\Model\Client;
use T3Monitor\T3monitoring\Domain\Model\Sla;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ClientTest extends UnitTestCase
{
    protected Client $instance;

    /**
     * Set up
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->instance = new Client();
    }

    #[Test]
    public function emailCanBeSet(): void
    {
        $subject = 'entry@fo.tld';
        $this->instance->setEmail($subject);
        self::assertEquals($subject, $this->instance->getEmail());
    }

    #[Test]
    public function titleCanBeSet(): void
    {
        $subject = 'Label';
        $this->instance->setTitle($subject);
        self::assertEquals($subject, $this->instance->getTitle());
    }

    #[Test]
    public function domainCanBeSet(): void
    {
        $subject = 'www.typo3.org';
        $this->instance->setDomain($subject);
        self::assertEquals($subject, $this->instance->getDomain());
    }

    #[Test]
    public function secretCanBeSet(): void
    {
        $subject = '1234';
        $this->instance->setSecret($subject);
        self::assertEquals($subject, $this->instance->getSecret());
    }

    #[Test]
    public function phpVersionCanBeSet(): void
    {
        $subject = '5.2';
        $this->instance->setPhpVersion($subject);
        self::assertEquals($subject, $this->instance->getPhpVersion());
    }

    #[Test]
    public function mysqlVersionCanBeSet(): void
    {
        $subject = '5.5';
        $this->instance->setMysqlVersion($subject);
        self::assertEquals($subject, $this->instance->getMysqlVersion());
    }

    #[Test]
    public function insecureCoreCanBeSet(): void
    {
        $this->instance->setInsecureCore(true);
        self::assertTrue($this->instance->getInsecureCore());
    }

    #[Test]
    public function insecureExtensionsCanBeSet(): void
    {
        $subject = 123;
        $this->instance->setInsecureExtensions($subject);
        self::assertEquals($subject, $this->instance->getInsecureExtensions());
    }

    #[Test]
    public function outdatedCoreCanBeSet(): void
    {
        $this->instance->setOutdatedCore(true);
        self::assertTrue($this->instance->getOutdatedCore());
    }

    #[Test]
    public function outdatedExtensionsCanBeSet(): void
    {
        $subject = 456;
        $this->instance->setOutdatedExtensions($subject);
        self::assertEquals($subject, $this->instance->getOutdatedExtensions());
    }

    #[Test]
    public function errorMessageCanBeSet(): void
    {
        $subject = 'error';
        $this->instance->setErrorMessage($subject);
        self::assertEquals($subject, $this->instance->getErrorMessage());
    }

    #[Test]
    public function extraInfoCanBeSet(): void
    {
        $subject = 'info';
        $this->instance->setExtraInfo($subject);
        self::assertEquals($subject, $this->instance->getExtraInfo());
    }

    #[Test]
    public function extraWarningCanBeSet(): void
    {
        $subject = 'warn';
        $this->instance->setExtraWarning($subject);
        self::assertEquals($subject, $this->instance->getExtraWarning());
    }

    #[Test]
    public function extraDangerCanBeSet(): void
    {
        $subject = 'danger';
        $this->instance->setExtraDanger($subject);
        self::assertEquals($subject, $this->instance->getExtraDanger());
    }

    #[Test]
    public function lastSuccessfulDateCanBeSet(): void
    {
        $subject = new \DateTime();
        $this->instance->setLastSuccessfulImport($subject);
        self::assertEquals($subject, $this->instance->getLastSuccessfulImport());
    }

    #[Test]
    public function slaCanBeSet(): void
    {
        $subject = new Sla();
        $subject->setTitle('sla');
        $this->instance->setSla($subject);
        self::assertEquals($subject, $this->instance->getSla());
    }
}
