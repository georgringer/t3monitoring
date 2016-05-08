<?php

namespace T3Monitor\T3monitoring\Command;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Extension;
use T3Monitor\T3monitoring\Notification\EmailNotification;
use T3Monitor\T3monitoring\Service\Import\ClientImport;
use T3Monitor\T3monitoring\Service\Import\CoreImport;
use T3Monitor\T3monitoring\Service\Import\ExtensionImport;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use TYPO3\CMS\Lang\LanguageService;
use UnexpectedValueException;

/**
 * CLI Tasks
 */
class MonitoringCommandController extends CommandController
{

    public function __construct()
    {
        $this->emailNotification = GeneralUtility::makeInstance(EmailNotification::class);
        $this->languageService = $GLOBALS['LANG'];
    }

    /**
     * Import core versions
     */
    public function importCoreCommand()
    {
        /** @var CoreImport $import */
        $import = $this->objectManager->get(CoreImport::class);
        $import->run();
    }

    /**
     * Import extensions
     */
    public function importExtensionsCommand()
    {
        /** @var ExtensionImport $import */
        $import = $this->objectManager->get(ExtensionImport::class);
        $import->run();
    }

    /**
     * Import clients
     */
    public function importClientsCommand()
    {
        /** @var ClientImport $import */
        $import = $this->objectManager->get(ClientImport::class);
        $import->run();

        $result = $import->getResponseCount();
        foreach ($result as $label => $count) {
            $this->outputLine(sprintf('%s: %s', $label, $count));
        }
    }

    /**
     * Import all: core, extensions, clients
     */
    public function importAllCommand()
    {
        $this->importCoreCommand();
        $this->importExtensionsCommand();
        $this->importClientsCommand();
    }

    /**
     * Generate basic report
     * @param string $email Send email to this email address
     */
    public function reportCommand($email = '')
    {
        $clients = $this->clientRepository->getAllForReport();

        if (count($clients) === 0) {
            $this->outputLine($this->getLabel('noInsecureClients'));
            return;
        }

        if (!empty($email)) {
            if (GeneralUtility::validEmail($email)) {
                $this->emailNotification->sendAdminEmail($email, $clients);
            } else {
                throw new UnexpectedValueException(sprintf('Email address "%s" is invalid!', $email));
            }
        } else {
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
     * @param string $key
     * @return string
     */
    protected function getLabel($key)
    {
        return $this->languageService->sL('LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:' . $key);
    }

    /** @var \T3Monitor\T3monitoring\Domain\Repository\ClientRepository */
    protected $clientRepository;

    public function injectClientRepository(\T3Monitor\T3monitoring\Domain\Repository\ClientRepository $repository)
    {
        $this->clientRepository = $repository;
    }

    /** @var EmailNotification */
    protected $emailNotification;

    /** @var LanguageService */
    protected $languageService;
}
