<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\Tests\Unit\Notification;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use PHPUnit\Framework\Attributes\Test;
use T3Monitor\T3monitoring\Domain\Model\Client;
use T3Monitor\T3monitoring\Notification\EmailNotification;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Class EmailNotificationTest
 */
class EmailNotificationTest extends UnitTestCase
{
    protected bool $resetSingletonInstances = true;

    #[Test]
    public function sendAdminEmailThrowsExceptionForInvalidEmailAddress(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $notification = new EmailNotification();
        $notification->sendAdminEmail('invalid', [new Client()]);
    }

    #[Test]
    public function sendAdminEmailThrowsExceptionForNoClients(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $notification = new EmailNotification();
        $notification->sendAdminEmail('john@doe.com', []);
    }

    #[Test]
    public function senderEmailNameIsCorrectlyReturned(): void
    {
        $notification = $this->getAccessibleMock(EmailNotification::class, ['dummy']);

        unset($GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromName']);
        self::assertEquals(EmailNotification::DEFAULT_EMAIL_NAME, $notification->_call('getSenderEmailName'));

        $example = $GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromName'] = 'John';
        self::assertEquals($example, $notification->_call('getSenderEmailName'));
    }

    #[Test]
    public function senderEmailAddressIsCorrectlyReturned(): void
    {
        $notification = $this->getAccessibleMock(EmailNotification::class, ['dummy']);

        unset($GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress']);
        self::assertEquals(EmailNotification::DEFAULT_EMAIL_ADDRESS, $notification->_call('getSenderEmailAddress'));

        $example = $GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress'] = 'someone@domain.tld';
        self::assertEquals($example, $notification->_call('getSenderEmailAddress'));
    }
}
