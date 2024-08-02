<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\Notification;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Mime\Address;
use T3Monitor\T3monitoring\Domain\Model\Client;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use UnexpectedValueException;

class EmailNotification implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const DEFAULT_EMAIL_NAME = 'EXT:t3monitoring';
    const DEFAULT_EMAIL_ADDRESS = 'no-reply@example.com';

    /**
     * @param string $email
     * @param Client[] $clients
     * @param string $subject
     * @throws UnexpectedValueException
     */
    public function sendAdminEmail(string $email, array $clients, string $subject = 'Monitoring Report'): void
    {
        if (!GeneralUtility::validEmail($email)) {
            throw new UnexpectedValueException('The email address is not valid');
        }

        if (count($clients) === 0) {
            throw new UnexpectedValueException('No clients given');
        }

        $arguments = [
            'email' => $email,
            'clients' => $clients
        ];
        $template = $this->getFluidTemplate($arguments, 'AdminEmail.txt', 'txt');
        $this->sendMail($email, $subject, $template);
    }

    /**
     * @param Client[] $clients
     * @param string $subject
     */
    public function sendClientEmail(array $clients, string $subject = 'Monitoring Report'): void
    {
        foreach ($clients as $client) {
            if (!GeneralUtility::validEmail($client->getEmail())) {
                continue;
            }
            $arguments = [
                'client' => $client
            ];
            $template = $this->getFluidTemplate($arguments, 'ClientEmail.txt', 'txt');
            $this->sendMail($client->getEmail(), $subject, $template);
        }
    }

    public function sendClientFailedEmail(array $clients, string $emailAddress, $subject = 'Monitoring Client Connection Failure'): void
    {
        if (empty($emailAddress)) {
            return;
        }
        if (!GeneralUtility::validEmail($emailAddress)) {
            $this->logger->warning(sprintf('The email address "%s" is not valid, no notification sent', $emailAddress));
        }
        $arguments = [
            'clients' => $clients,
            'email' => $emailAddress
        ];
        $template = $this->getFluidTemplate($arguments, 'ClientConnectionError.txt', 'txt');
        $this->sendMail($emailAddress, $subject, $template);
    }

    protected function sendMail(string $to, string $subject, string $plainContent, string $htmlContent = ''): bool
    {
        /** @var MailMessage $mailMessage */
        $mailMessage = GeneralUtility::makeInstance(MailMessage::class);
        $mailMessage
            ->setTo($to)
            ->setSubject($subject)
            ->text($plainContent)
            ->addFrom(new Address($this->getSenderEmailAddress(), $this->getSenderEmailName()));
        if (!empty($htmlContent)) {
            $mailMessage->html($htmlContent);
        }

        return $mailMessage->send();
    }

    /**
     * Creates a fluid instance with given template-file
     *
     * @param array $arguments
     * @param string $file Path below Template-Root-Path
     * @param string $format
     * @return string
     */
    protected function getFluidTemplate(array $arguments, string $file, string $format = 'html'): string
    {
        /** @var StandaloneView $renderer */
        $renderer = GeneralUtility::makeInstance(StandaloneView::class);
        $renderer->setFormat($format);
        $path = GeneralUtility::getFileAbsFileName('EXT:t3monitoring/Resources/Private/Templates/Notification/' . $file);
        $renderer->setTemplatePathAndFilename($path);
        $renderer->assignMultiple($arguments);

        return trim($renderer->render());
    }

    /**
     * Gets sender name from configuration
     * ['TYPO3_CONF_VARS']['MAIL']['defaultMailFromName']
     * If this setting is empty, it falls back to a default string.
     *
     * @return string
     */
    protected function getSenderEmailName(): string
    {
        return !empty($GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromName'])
            ? $GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromName']
            : self::DEFAULT_EMAIL_NAME;
    }

    /**
     * Gets sender email address from configuration
     * ['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress']
     * If this setting is empty, it falls back to a default string.
     *
     * @return string
     */
    protected function getSenderEmailAddress(): string
    {
        return !empty($GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress'])
            ? $GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress']
            : self::DEFAULT_EMAIL_ADDRESS;
    }
}
