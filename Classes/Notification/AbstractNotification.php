<?php
namespace T3Monitor\T3monitoring\Notification;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Notification\Channel\AbstractNotificationChannel;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class AbstractNotification
 */
abstract class AbstractNotification {
    /**
     * @var AbstractNotificationChannel[]
     */
    protected $channels;

    /**
     * @var array
     */
    protected $overrideChannelConfig;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var array
     */
    protected $data;

    /**
     * AbstractNotification constructor.
     *
     * @param string $subject
     * @param string $message
     * @param array $data
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($subject, $message = '', array $data = []) {
        $this->subject = $subject;
        $this->message = $message;
        $this->data = $data;

        $channels = $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3monitoring'][get_class($this)]['channels'];
        if (!empty($channels) && is_array($channels)) {
            foreach ($channels as $channelClass => $channelConfig) {
                $this->channels[] = GeneralUtility::makeInstance($channelClass, $channelConfig);
            }
        }
    }

    /**
     * Send notification to all configured channels
     */
    public function send() {
        if (!empty($this->channels)) {
            foreach ($this->channels as $channel) {
                if (!empty($this->overrideChannelConfig[get_class($channel)])) {
                    $options = $channel->getOptions();
                    ArrayUtility::mergeRecursiveWithOverrule($options, $this->overrideChannelConfig[get_class($channel)]);
                    $channel->setOptions($options);
                }
                $channel->process($this);
            }
        }
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOverrideChannelConfig()
    {
        return $this->overrideChannelConfig;
    }

    /**
     * @param mixed $overrideChannelConfig
     *
     * @return AbstractNotification
     */
    public function setOverrideChannelConfig($overrideChannelConfig)
    {
        $this->overrideChannelConfig = $overrideChannelConfig;
        return $this;
    }
}