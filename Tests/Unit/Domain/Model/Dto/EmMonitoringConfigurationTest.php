<?php
namespace T3Monitor\T3monitoring\Tests\Unit\Domain\Model\Dto;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Dto\EmMonitoringConfiguration;
use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * Class EmMonitoringConfigurationTest
 */
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

    /**
     * @param array $data
     */
    protected function setGlobalData(array $data)
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['t3monitoring'] = serialize($data);
    }
}
