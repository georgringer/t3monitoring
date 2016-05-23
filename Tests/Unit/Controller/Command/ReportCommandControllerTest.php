<?php
namespace T3Monitor\T3monitoring\Tests\Unit\Command\Controller;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Command\ReportCommandController;
use T3Monitor\T3monitoring\Domain\Repository\ClientRepository;
use T3Monitor\T3monitoring\Notification\EmailNotification;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Lang\LanguageService;

/**
 * Class ReportCommandControllerTest
 */
class ReportCommandControllerTest extends UnitTestCase
{

    /**
     * @test
     * @throws \InvalidArgumentException
     */
    public function reportCommandWillTriggerEmailNotifiction()
    {
        $dummyClients = ['123', '456'];
        $emailAddress = 'fo@bar.com';
        $mockedClientImport = $this->getAccessibleMock(ReportCommandController::class, ['outputLine'], [], '', false);

        $emailNotification = $this->prophesize(EmailNotification::class);

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
