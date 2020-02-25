<?php
namespace T3Monitor\T3monitoring\Tests\Unit\Command;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Prophecy\Prophecy\ObjectProphecy;
use T3Monitor\T3monitoring\Command\ReportClientCommand;
use T3Monitor\T3monitoring\Domain\Repository\ClientRepository;
use T3Monitor\T3monitoring\Notification\EmailNotification;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Class ReportCommandControllerTest
 */
class ReportCommandControllerTest extends UnitTestCase
{

    /**
     * @test
     * @throws \InvalidArgumentException
     */
    public function reportCommandWillTriggerEmailNotification()
    {
        // todo fix test
        $dummyClients = ['123', '456'];
        $emailAddress = 'fo@bar.com';
        /** @var ReportClientCommand|AccessibleObjectInterface $mockedClientImport */
        $mockedClientImport = $this->getAccessibleMock(ReportClientCommand::class, ['outputLine'], [], '', false);

        /** @var EmailNotification|ObjectProphecy $emailNotification */
        $emailNotification = $this->prophesize(EmailNotification::class);

        /** @var ClientRepository|ObjectProphecy $repository */
        $repository = $this->prophesize(ClientRepository::class);
        $repository->getAllForReport()->willReturn($dummyClients);
        $languageService = $this->prophesize(LanguageService::class);
        $mockedClientImport->_set('languageService', $languageService->reveal());
        $mockedClientImport->_set('clientRepository', $repository->reveal());
        $mockedClientImport->_set('emailNotification', $emailNotification->reveal());
        $emailNotification->sendAdminEmail($emailAddress, $dummyClients)->shouldBeCalled();

        $mockedClientImport->_call('adminCommand', 'fo@bar.com');
    }
}
