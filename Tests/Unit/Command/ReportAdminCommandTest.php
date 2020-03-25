<?php
namespace T3Monitor\T3monitoring\Tests\Unit\Command;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use T3Monitor\T3monitoring\Command\ReportAdminCommand;
use T3Monitor\T3monitoring\Notification\EmailNotification;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Class ReportCommandControllerTest
 */
class ReportAdminCommandTest extends UnitTestCase
{
    /**
     * @test
     */
    public function executeWillTriggerEmailNotification()
    {
        $dummyClients = ['123', '456'];
        $emailAddress = 'fo@bar.com';

        /** @var ReportAdminCommand|AccessibleObjectInterface $mockedClientImport */
        $mockedClientImport = $this->getAccessibleMock(ReportAdminCommand::class, ['dummy'], [], '', false);
        $mockedClientImport->_set('clients', $dummyClients);

        /** @var EmailNotification|ObjectProphecy $emailNotification */
        $emailNotification = $this->prophesize(EmailNotification::class);
        $emailNotification->sendAdminEmail($emailAddress, $dummyClients)->shouldBeCalled();
        GeneralUtility::addInstance(EmailNotification::class, $emailNotification->reveal());

        /** @var InputInterface|ObjectProphecy $input */
        $input = $this->prophesize(InputInterface::class);
        $input->getArgument('email')->willReturn($emailAddress);

        $mockedClientImport->_call('execute', $input->reveal(), $this->prophesize(OutputInterface::class)->reveal());
    }
}
