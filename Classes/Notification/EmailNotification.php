<?php
namespace T3Monitor\T3monitoring\Notification;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Client;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use UnexpectedValueException;

/**
 * Class EmailNotification
 */
class EmailNotification
{

    const DEFAULT_EMAIL_NAME = 'EXT:t3monitoring';
    const DEFAULT_EMAIL_ADDRESS = 'no-reply@example.com';

    /**
     * @param string $email
     * @param \T3Monitor\T3monitoring\Domain\Model\Client[] $clients
     * @param string $subject
     * @throws \UnexpectedValueException
     */
    public function sendAdminEmail($email, $clients, $subject = 'Monitoring Report')
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
     * @param \T3Monitor\T3monitoring\Domain\Model\Client[] $clients
     * @param string $subject
     */
    public function sendClientEmail($clients, $subject = 'Monitoring Report')
    {
        foreach ($clients as $client) {
            /** @var Client $client */
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

    /**
     * @param string $to
     * @param string $subject
     * @param string $plainContent
     * @param string $htmlContent
     * @return int
     */
    protected function sendMail($to, $subject, $plainContent, $htmlContent = '')
    {
        /** @var MailMessage $mailMessage */
        $mailMessage = GeneralUtility::makeInstance(MailMessage::class);
        $mailMessage
            ->setSubject($subject)
            ->addFrom($this->getSenderEmailAddress(), $this->getSenderEmailName())
            ->setTo($to)
            ->setBody($plainContent)->setContentType('text/plain');
        if (!empty($htmlContent)) {
            $mailMessage->addPart($htmlContent, 'text/emailText');
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
    protected function getFluidTemplate(array $arguments, $file, $format = 'html')
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
    protected function getSenderEmailName()
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
    protected function getSenderEmailAddress()
    {
        return !empty($GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress'])
            ? $GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress']
            : self::DEFAULT_EMAIL_ADDRESS;
    }
}
