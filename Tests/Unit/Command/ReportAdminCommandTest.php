<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\Tests\Unit\Command;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use PHPUnit\Framework\Attributes\Test;
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
    #[Test]
    public function executeWillTriggerEmailNotification()
    {
        $dummyClients = ['123', '456'];
        $emailAddress = 'fo@bar.com';

        /** @var ReportAdminCommand|AccessibleObjectInterface $mockedClientImport */
        $mockedClientImport = $this->getAccessibleMock(ReportAdminCommand::class, ['dummy'], [], '', false);
        $mockedClientImport->_set('clients', $dummyClients);

        $emailNotification = $this->createMock(EmailNotification::class);
        $emailNotification->expects($this->any())->method('sendAdminEmail')->with($emailAddress, $dummyClients);
        GeneralUtility::addInstance(EmailNotification::class, $emailNotification);

        $input = $this->createStub(InputInterface::class);
        $input->method('getArgument')->willReturn($emailAddress);

        $mockedClientImport->_call('execute', $input, GeneralUtility::makeInstance(OutputInterface::class));
    }
}
