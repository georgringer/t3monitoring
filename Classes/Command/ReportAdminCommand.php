<?php

namespace T3Monitor\T3monitoring\Command;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use T3Monitor\T3monitoring\Domain\Model\Client;
use T3Monitor\T3monitoring\Domain\Model\Extension;
use T3Monitor\T3monitoring\Domain\Repository\ClientRepository;
use T3Monitor\T3monitoring\Notification\EmailNotification;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Report command controller
 */
class ReportAdminCommand extends Command
{
    /** @var Client[] */
    protected $clients = [];

    /**
     * Configure the command by defining the name, options and arguments
     */
    protected function configure()
    {
        $this->addArgument('email', InputArgument::OPTIONAL, 'Email address to send report to', '');
        $this->setDescription('Generate collective report for all insecure clients (core or extensions)');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->clients = $objectManager->get(ClientRepository::class)->getAllForReport();
    }

    /**
     * Executes the command for adding the lock file
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \TYPO3\CMS\Extbase\Object\Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (count($this->clients) === 0) {
            $output->writeln($this->getLabel('noInsecureClients'));
            return 0;
        }

        $email = $input->getArgument('email');

        if ($email !== '') {
            if (GeneralUtility::validEmail($email)) {
                GeneralUtility::makeInstance(EmailNotification::class)->sendAdminEmail($email, $this->clients);
            } else {
                throw new \UnexpectedValueException(sprintf('Email address "%s" is invalid!', $email));
            }
        } else {
            $collectedClientData = [];
            foreach ($this->clients as $client) {
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
            $style = new SymfonyStyle($input, $output);
            $style->table($header, $collectedClientData);
        }
        return 0;
    }

    protected function getLabel(string $key): string
    {
        /** @var LanguageService $languageService */
        $languageService = $GLOBALS['LANG'];
        return $languageService->sL('LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:' . $key);
    }
}
