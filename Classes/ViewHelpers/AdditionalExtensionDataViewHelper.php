<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\ViewHelpers;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class AdditionalExtensionDataViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('client', 'int', 'Client', true);
        $this->registerArgument('extension', 'int', 'Extension', true);
        $this->registerArgument('as', 'string', 'Output variable', true);
    }

    public function render(): string
    {
        $queryBuilderCoreExtensions = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_t3monitoring_client_extension_mm');
        $result = $queryBuilderCoreExtensions
            ->select('is_loaded', 'state', 'title')
            ->from('tx_t3monitoring_client_extension_mm')
            ->where(
                $queryBuilderCoreExtensions->expr()->eq('uid_local', $queryBuilderCoreExtensions->createNamedParameter($this->arguments['client'], Connection::PARAM_INT)),
                $queryBuilderCoreExtensions->expr()->eq('uid_foreign', $queryBuilderCoreExtensions->createNamedParameter($this->arguments['extension'], Connection::PARAM_INT))
            )
            ->setMaxResults(1)
            ->executeQuery()->fetchAssociative();

        $this->renderingContext->getVariableProvider()->add($this->arguments['as'], $result);
        $output = $this->renderChildren();
        $this->renderingContext->getVariableProvider()->remove($this->arguments['as']);

        return $output;
    }
}
