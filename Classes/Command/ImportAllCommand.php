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
use T3Monitor\T3monitoring\Service\Import\ClientImport;
use T3Monitor\T3monitoring\Service\Import\CoreImport;
use T3Monitor\T3monitoring\Service\Import\ExtensionImport;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Monitoring command controller
 */
class ImportAllCommand extends Command
{

    /**
     * Configure the command by defining the name, options and arguments
     */
    protected function configure()
    {
        $this->setDescription('Import all: core, extensions, clients');
    }

    /**
     * Executes the command for adding the lock file
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \TYPO3\CMS\Extbase\Object\Exception
     * @throws \TYPO3\CMS\Extensionmanager\Exception\ExtensionManagerException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $objectManager->get(CoreImport::class)->run();
        $objectManager->get(ExtensionImport::class)->run();
        $import = $objectManager->get(ClientImport::class);
        $import->run();

        $result = $import->getResponseCount();
        foreach ($result as $label => $count) {
            $output->writeln(sprintf('%s: %s', $label, $count));
        }
    }
}
