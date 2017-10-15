<?php
namespace T3Monitor\T3monitoring\Notification\Channel;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Notification\AbstractNotification;

/**
 * Interface NotificationChannelInterface
 */
interface NotificationChannelInterface {
    public function process(AbstractNotification $notification);
    public function getOptions();
    public function setOptions(array $options);
}