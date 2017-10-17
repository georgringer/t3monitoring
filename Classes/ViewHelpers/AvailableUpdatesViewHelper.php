<?php

namespace T3Monitor\T3monitoring\ViewHelpers;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Extension;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Fluid\Core\ViewHelper\Facets\CompilableInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Class AvailableUpdatesViewHelper
 */
class AvailableUpdatesViewHelper extends AbstractViewHelper implements CompilableInterface
{
    use CompileWithRenderStatic;

    /** @var bool */
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        $this->registerArgument('extension', Extension::class, 'Extension', true);
        $this->registerArgument('as', 'string', 'Output variable', false, 'list');
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        /** @var Extension $extension */
        $extension = $arguments['extension'];

        $versions = [
            'bugfix' => $extension->getLastBugfixRelease(),
            'minor' => $extension->getLastMinorRelease(),
            'major' => $extension->getLastMajorRelease()
        ];

        $result = [];
        foreach ($versions as $name => $version) {
            if (!empty($version) && $extension->getVersion() !== $version && !isset($result[$version])) {
                $result[$version] = [
                    'name' => $name,
                    'version' => $version,
                    'serializedDependencies' => self::getDependenciesOfExtensionVersion($extension->getName(), $version),
                ];
            }
        }

        $renderingContext->getVariableProvider()->add($arguments['as'], $result);
        $output = $renderChildrenClosure();
        $renderingContext->getVariableProvider()->remove($arguments['as']);

        return $output;
    }

    /**
     * @param string $name
     * @param string $version
     * @return string
     */
    protected static function getDependenciesOfExtensionVersion(string $name, string $version): string
    {
        $table = 'tx_t3monitoring_domain_model_extension';

        $queryBuilderCoreExtensions = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table);
        $row = $queryBuilderCoreExtensions
            ->select('serialized_dependencies')
            ->from($table)
            ->where(
                $queryBuilderCoreExtensions->expr()->eq('name', $queryBuilderCoreExtensions->createNamedParameter($name)),
                $queryBuilderCoreExtensions->expr()->eq('version', $queryBuilderCoreExtensions->createNamedParameter($version))
            )
            ->setMaxResults(1)
            ->execute()->fetch();

        if ($row) {
            return (string)$row['serialized_dependencies'];
        }
        return '';
    }
}
