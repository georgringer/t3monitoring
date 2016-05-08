<?php

namespace T3Monitor\T3monitoring\Tests\Unit\Domain\Model\Dto;

use T3Monitor\T3monitoring\Domain\Model\Dto\EmMonitoringConfiguration;
use TYPO3\CMS\Core\Tests\UnitTestCase;

class EmMonitoringConfigurationTest extends UnitTestCase
{

    /**
     * @test
     */
    public function pidCanBeRead()
    {
        $subject = 123;
        $this->setGlobalData(['pid' => $subject]);
        $instance = new EmMonitoringConfiguration();
        $this->assertEquals($subject, $instance->getPid());
    }

    /**
     * @test
     */
    public function loadBulletinsCanBeRead()
    {
        $subject = false;
        $this->setGlobalData(['loadBulletins' => $subject]);
        $instance = new EmMonitoringConfiguration();
        $this->assertEquals($subject, $instance->getLoadBulletins());
    }

    protected function setGlobalData(array $data)
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['t3monitoring'] = serialize($data);
    }
}
