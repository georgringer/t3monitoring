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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use T3Monitor\T3monitoring\Domain\Repository\ClientRepository;
use T3Monitor\T3monitoring\Notification\EmailNotification;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ReportClientCommand extends Command
{
    protected function configure(): void
    {
        $this->setDescription('Report clients');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $clients = GeneralUtility::makeInstance(ClientRepository::class)->getAllForReport(true);
        if (count($clients) === 0) {
            $output->writeln($this->getLabel('noInsecureClients'));
            return Command::SUCCESS;
        }

        GeneralUtility::makeInstance(EmailNotification::class)->sendClientEmail($clients);
        return Command::SUCCESS;
    }

    protected function getLabel(string $key): string
    {
        /** @var LanguageService $languageService */
        $languageService = $GLOBALS['LANG'];
        return $languageService->sL('LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:' . $key);
    }
}
