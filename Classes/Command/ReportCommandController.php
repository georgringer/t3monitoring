<?php
namespace T3Monitor\T3monitoring\Command;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Extension;
use T3Monitor\T3monitoring\Domain\Repository\ClientRepository;
use T3Monitor\T3monitoring\Notification\EmailNotification;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use TYPO3\CMS\Lang\LanguageService;
use UnexpectedValueException;

/**
 * Report command controller
 */
class ReportCommandController extends CommandController
{

    /** @var EmailNotification */
    protected $emailNotification;

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
        $this->emailNotification = GeneralUtility::makeInstance(EmailNotification::class);
        $this->languageService = $GLOBALS['LANG'];
    }

    /**
     * Generate collective report for all insecure clients (core or extensions)
     *
     * @param string $email Send email to this email address
     * @throws \UnexpectedValueException
     */
    public function adminCommand($email = '')
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
     * Client command
     */
    public function clientCommand()
    {
        $clients = $this->clientRepository->getAllForReport(true);

        if (count($clients) === 0) {
            $this->outputLine($this->getLabel('noInsecureClients'));
            return;
        }

        $this->emailNotification->sendClientEmail($clients);
    }

    /**
     * @param string $key
     * @return string
     */
    protected function getLabel($key)
    {
        return $this->languageService->sL('LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:' . $key);
    }

}
