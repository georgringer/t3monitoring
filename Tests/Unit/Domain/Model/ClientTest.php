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
    public function emailCanBeSet()
    {
        $subject = 'entry@fo.tld';
        $this->instance->setEmail($subject);
        $this->assertEquals($subject, $this->instance->getEmail());
    }

    #[Test]
    public function titleCanBeSet()
    {
        $subject = 'Label';
        $this->instance->setTitle($subject);
        $this->assertEquals($subject, $this->instance->getTitle());
    }

    #[Test]
    public function domainCanBeSet()
    {
        $subject = 'www.typo3.org';
        $this->instance->setDomain($subject);
        $this->assertEquals($subject, $this->instance->getDomain());
    }

    #[Test]
    public function secretCanBeSet()
    {
        $subject = '1234';
        $this->instance->setSecret($subject);
        $this->assertEquals($subject, $this->instance->getSecret());
    }

    #[Test]
    public function phpVersionCanBeSet()
    {
        $subject = '5.2';
        $this->instance->setPhpVersion($subject);
        $this->assertEquals($subject, $this->instance->getPhpVersion());
    }

    #[Test]
    public function mysqlVersionCanBeSet()
    {
        $subject = '5.5';
        $this->instance->setMysqlVersion($subject);
        $this->assertEquals($subject, $this->instance->getMysqlVersion());
    }

    #[Test]
    public function insecureCoreCanBeSet()
    {
        $this->instance->setInsecureCore(true);
        $this->assertTrue($this->instance->getInsecureCore());
    }

    #[Test]
    public function insecureExtensionsCanBeSet()
    {
        $subject = 123;
        $this->instance->setInsecureExtensions($subject);
        $this->assertEquals($subject, $this->instance->getInsecureExtensions());
    }

    #[Test]
    public function outdatedCoreCanBeSet()
    {
        $this->instance->setOutdatedCore(true);
        $this->assertTrue($this->instance->getOutdatedCore());
    }

    #[Test]
    public function outdatedExtensionsCanBeSet()
    {
        $subject = 456;
        $this->instance->setOutdatedExtensions($subject);
        $this->assertEquals($subject, $this->instance->getOutdatedExtensions());
    }

    #[Test]
    public function errorMessageCanBeSet()
    {
        $subject = 'error';
        $this->instance->setErrorMessage($subject);
        $this->assertEquals($subject, $this->instance->getErrorMessage());
    }

    #[Test]
    public function extraInfoCanBeSet()
    {
        $subject = 'info';
        $this->instance->setExtraInfo($subject);
        $this->assertEquals($subject, $this->instance->getExtraInfo());
    }

    #[Test]
    public function extraWarningCanBeSet()
    {
        $subject = 'warn';
        $this->instance->setExtraWarning($subject);
        $this->assertEquals($subject, $this->instance->getExtraWarning());
    }

    #[Test]
    public function extraDangerCanBeSet()
    {
        $subject = 'danger';
        $this->instance->setExtraDanger($subject);
        $this->assertEquals($subject, $this->instance->getExtraDanger());
    }

    #[Test]
    public function lastSuccessfulDateCanBeSet()
    {
        $subject = new \DateTime();
        $this->instance->setLastSuccessfulImport($subject);
        $this->assertEquals($subject, $this->instance->getLastSuccessfulImport());
    }

    #[Test]
    public function slaCanBeSet()
    {
        $subject = new Sla();
        $subject->setTitle('sla');
        $this->instance->setSla($subject);
        $this->assertEquals($subject, $this->instance->getSla());
    }
}
