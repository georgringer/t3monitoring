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
 * Class AbstractNotificationChannel
 */
abstract class AbstractNotificationChannel implements NotificationChannelInterface {
    /**
     * @var array
     */
    protected $options;

    public function __construct(array $options) {
        $this->options = $options;
    }

    /**
     * @param AbstractNotification $notification
     */
    abstract public function process(AbstractNotification $notification);

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     *
     * @return AbstractNotificationChannel
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }
}