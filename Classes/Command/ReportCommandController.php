<?php
namespace T3Monitor\T3monitoring\Command;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Client;
use T3Monitor\T3monitoring\Domain\Model\Extension;
use T3Monitor\T3monitoring\Domain\Repository\ClientRepository;
use T3Monitor\T3monitoring\Notification\Channel\EmailChannel;
use T3Monitor\T3monitoring\Notification\ReportNotification;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Lang\LanguageService;
use UnexpectedValueException;

/**
 * Report command controller
 */
class ReportCommandController extends CommandController
{
    /** @var LanguageService */
    protected $languageService;

    /** @var ClientRepository */
    protected $clientRepository;

    /**
     * @param \T3Monitor\T3monitoring\Domain\Repository\ClientRepository $clientRepository
     */
    public function injectClientRepository(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->languageService = $GLOBALS['LANG'];
    }

    /**
     * Generate collective report for all insecure clients (core or extensions)
     *
     * @param string $email Send email to this email address
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function adminCommand($email = '')
    {
        $clients = $this->clientRepository->getAllForReport();

        if (count($clients) === 0) {
            $this->outputLine($this->getLabel('noInsecureClients'));
            return;
        }

        $arguments = [
            'email' => $email,
            'clients' => $clients
        ];
        $text = $this->getFluidTemplate($arguments, 'AdminEmail.txt', 'txt');
        $notification = GeneralUtility::makeInstance(ReportNotification::class, 'Monitoring Report', $text);
        if (!empty($email)) {
            if (!GeneralUtility::validEmail($email)) {
                throw new UnexpectedValueException(sprintf('Email address "%s" is invalid!', $email));
            }

            $channelOverrideConfiguration = $notification->getOverrideChannelConfig();
            $channelOverrideConfiguration[EmailChannel::class] = [
                'recipientAddress' => $email
            ];
            $notification->setOverrideChannelConfig($channelOverrideConfiguration);
        }
        $notification->send();

        if (empty($email)) {
            $collectedClientData = [];
            foreach ($clients as $client) {
                $insecureExtensions = [];
                if ($client->getInsecureExtensions()) {
                    $extensions = $client->getExtensions();
                    foreach ($extensions as $extension) {
                        /** @var Extension $extension */
                        if ($extension->isInsecure()) {
                            $insecureExtensions[] = sprintf('%s (%s)', $extension->getName(), $extension->getVersion());
                        }
                    }
                }

                $collectedClientData[] = [
                    $client->getTitle(),
                    $client->getCore()->isInsecure() ? $client->getCore()->getVersion() : '✓',
                    $insecureExtensions ? implode(', ', $insecureExtensions) : ''
                ];
            }

            $header = [
                $this->getLabel('tx_t3monitoring_domain_model_client'),
                $this->getLabel('tx_t3monitoring_domain_model_client.insecure_core'),
                $this->getLabel('tx_t3monitoring_domain_model_client.insecure_extensions'),
            ];
            $this->output->outputTable($collectedClientData, $header);
        }
    }

    /**
     * Client command
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     */
    public function clientCommand()
    {
        $clients = $this->clientRepository->getAllForReport(true);

        if (count($clients) === 0) {
            $this->outputLine($this->getLabel('noInsecureClients'));
            return;
        }

        /** @var Client $client */
        foreach ($clients as $client) {
            $email = $client->getEmail();
            if (!GeneralUtility::validEmail($email)) {
                continue;
            }
            $arguments = [
                'email' => $email,
                'client' => $client
            ];
            $text = $this->getFluidTemplate($arguments, 'ClientEmail.txt', 'txt');
            $notification = GeneralUtility::makeInstance(ReportNotification::class, 'Monitoring Report', $text);
            if (!empty($email)) {
                if (!GeneralUtility::validEmail($email)) {
                    throw new UnexpectedValueException(sprintf('Email address "%s" is invalid!', $email));
                }
                $channelOverrideConfiguration = $notification->getOverrideChannelConfig();
                $channelOverrideConfiguration[EmailChannel::class] = [
                    'recipientAddress' => $email
                ];
                $notification->setOverrideChannelConfig($channelOverrideConfiguration);
            }
            $notification->send();
        }
    }

    /**
     * @param string $key
     * @return string
     */
    protected function getLabel($key)
    {
        return $this->languageService->sL('LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:' . $key);
    }

    /**
     * Creates a fluid instance with given template-file
     *
     * @param array $arguments
     * @param string $file Path below Template-Root-Path
     * @param string $format
     *
     * @return string
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
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
}
