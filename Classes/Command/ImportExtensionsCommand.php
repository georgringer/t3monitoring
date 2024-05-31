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
use T3Monitor\T3monitoring\Service\Import\ExtensionImport;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Monitoring command controller
 */
class ImportExtensionsCommand extends Command
{
    /**
     * Configure the command by defining the name, options and arguments
     */
    protected function configure()
    {
        $this->setDescription('Import extensions');
    }

    /**
     * Executes the command for adding the lock file
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \TYPO3\CMS\Extbase\Object\Exception
     * @throws \TYPO3\CMS\Extensionmanager\Exception\ExtensionManagerException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        GeneralUtility::makeInstance(ExtensionImport::class)->run();
        return Command::SUCCESS;
    }
}
