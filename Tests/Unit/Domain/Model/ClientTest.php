<?php

namespace T3Monitor\T3monitoring\Tests\Unit\Domain\Model\Dto;

use T3Monitor\T3monitoring\Domain\Model\Client;
use T3Monitor\T3monitoring\Domain\Model\Sla;
use TYPO3\CMS\Core\Tests\UnitTestCase;

class ClientTest extends UnitTestCase
{

    /**
     * @var Client
     */
    protected $instance;

    protected function setUp()
    {
        $this->instance = new Client();
    }

    /**
     * @test
     */
    public function emailCanBeSet()
    {
        $subject = 'entry@fo.tld';
        $this->instance->setEmail($subject);
        $this->assertEquals($subject, $this->instance->getEmail());
    }

    /**
     * @test
     */
    public function titleCanBeSet()
    {
        $subject = 'Label';
        $this->instance->setTitle($subject);
        $this->assertEquals($subject, $this->instance->getTitle());
    }

    /**
     * @test
     */
    public function domainCanBeSet()
    {
        $subject = 'www.typo3.org';
        $this->instance->setDomain($subject);
        $this->assertEquals($subject, $this->instance->getDomain());
    }

    /**
     * @test
     */
    public function secretCanBeSet()
    {
        $subject = '1234';
        $this->instance->setSecret($subject);
        $this->assertEquals($subject, $this->instance->getSecret());
    }

    /**
     * @test
     */
    public function phpVersionCanBeSet()
    {
        $subject = '5.2';
        $this->instance->setPhpVersion($subject);
        $this->assertEquals($subject, $this->instance->getPhpVersion());
    }

    /**
     * @test
     */
    public function mysqlVersionCanBeSet()
    {
        $subject = '5.5';
        $this->instance->setMysqlVersion($subject);
        $this->assertEquals($subject, $this->instance->getMysqlVersion());
    }

    /**
     * @test
     */
    public function insecureCoreCanBeSet()
    {
        $subject = true;
        $this->instance->setInsecureCore($subject);
        $this->assertEquals($subject, $this->instance->getInsecureCore());
    }

    /**
     * @test
     */
    public function insecureExtensionsCanBeSet()
    {
        $subject = 123;
        $this->instance->setInsecureExtensions($subject);
        $this->assertEquals($subject, $this->instance->getInsecureExtensions());
    }

    /**
     * @test
     */
    public function ouddatedCoreCanBeSet()
    {
        $subject = true;
        $this->instance->setOutdatedCore($subject);
        $this->assertEquals($subject, $this->instance->getOutdatedCore());
    }

    /**
     * @test
     */
    public function outdatedExtensionsCanBeSet()
    {
        $subject = 456;
        $this->instance->setOutdatedExtensions($subject);
        $this->assertEquals($subject, $this->instance->getOutdatedExtensions());
    }

    /**
     * @test
     */
    public function errorMessageCanBeSet()
    {
        $subject = 'error';
        $this->instance->setErrorMessage($subject);
        $this->assertEquals($subject, $this->instance->getErrorMessage());
    }

    /**
     * @test
     */
    public function extraInfoCanBeSet()
    {
        $subject = 'info';
        $this->instance->setExtraInfo($subject);
        $this->assertEquals($subject, $this->instance->getExtraInfo());
    }

    /**
     * @test
     */
    public function extraWarningCanBeSet()
    {
        $subject = 'warn';
        $this->instance->setExtraWarning($subject);
        $this->assertEquals($subject, $this->instance->getExtraWarning());
    }

    /**
     * @test
     */
    public function extraDangerCanBeSet()
    {
        $subject = 'danger';
        $this->instance->setExtraDanger($subject);
        $this->assertEquals($subject, $this->instance->getExtraDanger());
    }

    /**
     * @test
     */
    public function lastSuccessfulDateCanBeSet()
    {
        $subject = new \DateTime();
        $this->instance->setLastSuccessfulImport($subject);
        $this->assertEquals($subject, $this->instance->getLastSuccessfulImport());
    }

    /**
     * @test
     */
    public function slaCanBeSet()
    {
        $subject = new Sla();
        $subject->setTitle('sla');
        $this->instance->setSla($subject);
        $this->assertEquals($subject, $this->instance->getSla());
    }
}
