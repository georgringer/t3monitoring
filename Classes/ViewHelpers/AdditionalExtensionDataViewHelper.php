<?php

namespace T3Monitor\T3monitoring\ViewHelpers;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Fluid\Core\ViewHelper\Facets\CompilableInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Class AdditionalExtensionDataViewHelper
 */
class AdditionalExtensionDataViewHelper extends AbstractViewHelper implements CompilableInterface
{
    use CompileWithRenderStatic;

    /** @var bool */
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        $this->registerArgument('client', 'int', 'Client', true);
        $this->registerArgument('extension', 'int', 'Extension', true);
        $this->registerArgument('as', 'string', 'Output variable', true);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $queryBuilderCoreExtensions = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_t3monitoring_client_extension_mm');
        $result = $queryBuilderCoreExtensions
            ->select('is_loaded', 'state', 'title')
            ->from('tx_t3monitoring_client_extension_mm')
            ->where(
                $queryBuilderCoreExtensions->expr()->eq('uid_local', $queryBuilderCoreExtensions->createNamedParameter($arguments['client'], \PDO::PARAM_INT)),
                $queryBuilderCoreExtensions->expr()->eq('uid_foreign', $queryBuilderCoreExtensions->createNamedParameter($arguments['extension'], \PDO::PARAM_INT))
            )
            ->setMaxResults(1)
            ->execute()->fetch();

        $renderingContext->getVariableProvider()->add($arguments['as'], $result);
        $output = $renderChildrenClosure();
        $renderingContext->getVariableProvider()->remove($arguments['as']);

        return $output;
    }
}
