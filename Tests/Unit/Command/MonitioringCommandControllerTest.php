<?php
namespace T3Monitor\T3monitoring\Tests\Unit\Command;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Command\MonitoringCommandController;
use T3Monitor\T3monitoring\Service\Import\ClientImport;
use T3Monitor\T3monitoring\Service\Import\CoreImport;
use T3Monitor\T3monitoring\Service\Import\ExtensionImport;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class MonitoringCommandControllerTest
 */
class MonitioringCommandControllerTest extends UnitTestCase
{

    /**
     * @test
     */
    public function coreImportCommandWillTriggerCoreImport()
    {
        $mockedCommand = $this->getAccessibleMock(MonitoringCommandController::class, ['dummy'], [], '', false);
        $mockedCoreImport = $this->prophesize(CoreImport::class);
        $mockedCoreImport->run()->shouldBeCalled();

        $objectManager = $this->prophesize(ObjectManager::class);
        $objectManager->get(CoreImport::class)->willReturn($mockedCoreImport);

        $mockedCommand->_set('objectManager', $objectManager->reveal());
        $mockedCommand->_call('importCoreCommand');
    }

    /**
     * @test
     */
    public function extensionImportCommandWillTriggerExtensionImport()
    {
        $mockedCommand = $this->getAccessibleMock(MonitoringCommandController::class, ['dummy'], [], '', false);
        $mockedImportExtensions = $this->prophesize(ExtensionImport::class);
        $mockedImportExtensions->run()->shouldBeCalled();

        $objectManager = $this->prophesize(ObjectManager::class);
        $objectManager->get(ExtensionImport::class)->willReturn($mockedImportExtensions);

        $mockedCommand->_set('objectManager', $objectManager->reveal());
        $mockedCommand->_call('importExtensionsCommand');
    }

    /**
     * @test
     */
    public function clientImportCommandWillTriggerClientImport()
    {
        $mockedCommand = $this->getAccessibleMock(MonitoringCommandController::class, ['dummy'], [], '', false);
        $mockedImport = $this->prophesize(ClientImport::class);
        $mockedImport->getResponseCount()->willReturn([]);
        $mockedImport->run()->shouldBeCalled();

        $objectManager = $this->prophesize(ObjectManager::class);
        $objectManager->get(ClientImport::class)->willReturn($mockedImport);

        $mockedCommand->_set('objectManager', $objectManager->reveal());
        $mockedCommand->_call('importClientsCommand');
    }

    /**
     * @test
     */
    public function allImportCommandWillTriggerAllImports()
    {
        $mockedCommand = $this->getAccessibleMock(MonitoringCommandController::class, ['dummy'], [], '', false);
        $mockedImportClient = $this->prophesize(ClientImport::class);
        $mockedImportClient->getResponseCount()->willReturn([]);
        $mockedImportClient->run()->shouldBeCalled();
        $mockedCoreImport = $this->prophesize(CoreImport::class);
        $mockedCoreImport->run()->shouldBeCalled();
        $mockedImportExtensions = $this->prophesize(ExtensionImport::class);
        $mockedImportExtensions->run()->shouldBeCalled();

        $objectManager = $this->prophesize(ObjectManager::class);
        $objectManager->get(ClientImport::class)->willReturn($mockedImportClient);
        $objectManager->get(CoreImport::class)->willReturn($mockedCoreImport);
        $objectManager->get(ExtensionImport::class)->willReturn($mockedImportExtensions);

        $mockedCommand->_set('objectManager', $objectManager->reveal());
        $mockedCommand->_call('importAllCommand');
    }
}
