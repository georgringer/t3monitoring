<?php
namespace T3Monitor\T3monitoring\Notification\Channel;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Notification\AbstractNotification;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class SlackChannel
 * Options:
 * - webHookUrl (required, get it from your slack team integration)
 * - channel (required, e.g. #monitoring)
 * - username (optional, default: MonitoringBot)
 * - color (optional, good, warning, danger or a hexcode like #ff3636 (red), default: danger)
 */
class SlackChannel extends AbstractNotificationChannel
{
    /**
     * @param AbstractNotification $notification
     *
     * @throws \InvalidArgumentException
     */
    public function process(AbstractNotification $notification)
    {
        $requestFactory = GeneralUtility::makeInstance(RequestFactory::class);
        $webHookUrl = $this->getOptions()['webHookUrl'];
        $additionalOptions = [
            'headers' => ['Cache-Control' => 'no-cache', 'content-type' => 'application/json'],
            'allow_redirects' => false
        ];
        $json = [
            'channel' => $this->getOptions()['channel'],
            'attachments' => [$this->buildAttachment($notification)],
            'username' => !empty($this->getOptions()['username']) ? $this->getOptions()['username'] : 'MonitoringBot',
        ];
        $additionalOptions['body'] = json_encode($json);
        $requestFactory->request($webHookUrl, 'POST', $additionalOptions);
    }

    /**
     * @param AbstractNotification $notification
     *
     * @return \stdClass
     */
    protected function buildAttachment(AbstractNotification $notification)
    {
        $attachment = new \stdClass();
        $attachment->fallback = $notification->getSubject() . ': ' . $notification->getMessage();
        $attachment->color = !empty($this->getOptions()['color']) ? $this->getOptions()['color'] : 'danger';
        $attachment->author_name = !empty($this->getOptions()['username']) ? $this->getOptions()['username'] : 'MonitoringBot';
        $attachment->title = $notification->getSubject();
        $attachment->text = $notification->getMessage();
        if (count($notification->getData())) {
            $attachment->fields = [];
        }
        foreach ($notification->getData() as $key => $value) {
            $tmp = new \stdClass();
            $tmp->title = $key;
            $tmp->value = is_string($value) ? $value : print_r($value, true);
            $attachment->fields[] = $tmp;
        }
        return $attachment;
    }
}