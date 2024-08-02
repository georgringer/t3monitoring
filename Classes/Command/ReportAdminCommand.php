<?php

declare(strict_types=1);

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

class ReportAdminCommand extends Command
{
    /** @var Client[] */
    protected array $clients = [];

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::OPTIONAL, 'Email address to send report to', '');
        $this->setDescription('Generate collective report for all insecure clients (core or extensions)');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        $this->clients = GeneralUtility::makeInstance(ClientRepository::class)->getAllForReport();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (count($this->clients) === 0) {
            $output->writeln($this->getLabel('noInsecureClients'));
            return Command::SUCCESS;
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
        return Command::SUCCESS;
    }

    protected function getLabel(string $key): string
    {
        /** @var LanguageService $languageService */
        $languageService = $GLOBALS['LANG'];
        return $languageService->sL('LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:' . $key);
    }
}
