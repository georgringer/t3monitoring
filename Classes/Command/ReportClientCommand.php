<?php

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
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Report command controller
 */
class ReportClientCommand extends Command
{
    /**
     * Configure the command by defining the name, options and arguments
     */
    protected function configure()
    {
        $this->setDescription('Report clients');
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
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $clients = $objectManager->get(ClientRepository::class)->getAllForReport(true);
        if (count($clients) === 0) {
            $output->writeln($this->getLabel('noInsecureClients'));
            return;
        }

        GeneralUtility::makeInstance(EmailNotification::class)->sendClientEmail($clients);
    }

    protected function getLabel(string $key): string
    {
        /** @var LanguageService $languageService */
        $languageService = $GLOBALS['LANG'];
        return $languageService->sL('LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:' . $key);
    }
}
