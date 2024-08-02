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
use T3Monitor\T3monitoring\Service\Import\ClientImport;
use T3Monitor\T3monitoring\Service\Import\CoreImport;
use T3Monitor\T3monitoring\Service\Import\ExtensionImport;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ImportAllCommand extends Command
{
    protected function configure(): void
    {
        $this->setDescription('Import all: core, extensions, clients');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        GeneralUtility::makeInstance(CoreImport::class)->run();
        GeneralUtility::makeInstance(ExtensionImport::class)->run();
        $import = GeneralUtility::makeInstance(ClientImport::class);
        $import->run();

        $result = $import->getResponseCount();
        foreach ($result as $label => $count) {
            $output->writeln(sprintf('%s: %s', $label, $count));
        }
        return Command::SUCCESS;
    }
}
