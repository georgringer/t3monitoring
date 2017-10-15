<?php
namespace T3Monitor\T3monitoring\Notification\Channel;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Notification\AbstractNotification;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class EmailChannel
 * Options:
 * - senderAddress
 * - senderName
 * - recipientAddress
 */
class EmailChannel extends AbstractNotificationChannel
{
    /**
     * @param AbstractNotification $notification
     *
     * @throws \InvalidArgumentException
     */
    public function process(AbstractNotification $notification)
    {
        $mailMessage = GeneralUtility::makeInstance(MailMessage::class);
        $mailMessage
            ->setSubject($notification->getSubject())
            ->addFrom($this->getOptions()['senderAddress'], $this->getOptions()['senderName'])
            ->setTo($this->getOptions()['recipientAddress'])
            ->setBody($notification->getMessage())
            ->setContentType('text/plain');
        $mailMessage->send();
    }
}