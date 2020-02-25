<?php

namespace T3Monitor\T3monitoring\ViewHelpers;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Core;
use T3Monitor\T3monitoring\Domain\Model\Extension;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class AvailableUpdatesViewHelper
 */
class AvailableUpdatesViewHelper extends AbstractViewHelper
{
    /** @var bool */
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('extension', Extension::class, 'Extension', true);
        $this->registerArgument('core', Core::class, 'Core', true);
        $this->registerArgument('as', 'string', 'Output variable', false, 'list');
    }

    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        /** @var Extension $extension */
        $extension = $arguments['extension'];
        /** @var Core $core */
        $core = $arguments['core'];

        $versions = [
            'bugfix' => $extension->getLastBugfixRelease(),
            'minor' => $extension->getLastMinorRelease(),
            'major' => $extension->getLastMajorRelease()
        ];

        $result = [];
        foreach ($versions as $name => $version) {
            if (!empty($version) && $extension->getVersion() !== $version && !isset($result[$version])) {
                $extDetails = self::getExtDetails($extension->getName(), $version);
                $result[$version] = [
                    'name' => $name,
                    'version' => $version,
                    'identifier' => 'id-' . md5($name . $version),
                    'typo3MinVersion' => $extDetails['typo3_min_version'],
                    'typo3MaxVersion' => $extDetails['typo3_max_version'],
                    'coreVersion' => $core->getVersionInteger(),
                    'extCompatibility' => self::getCompatibility($extDetails['typo3_min_version'], $extDetails['typo3_max_version'], $core),
                    'serializedDependencies' => $extDetails['serialized_dependencies'],
                ];
            }
        }

        $renderingContext->getVariableProvider()->add($arguments['as'], $result);
        $output = $renderChildrenClosure();
        $renderingContext->getVariableProvider()->remove($arguments['as']);

        return $output;
    }

    protected static function getCompatibility(int $extMin, int $extMax, Core $core): int
    {
        $coreVersion = $core->getVersionInteger();
        if (!$coreVersion || !$extMin || !$extMax) {
            return -1;
        }

        if ($coreVersion >= $extMin && $coreVersion <= $extMax) {
            return 1;
        }
        return 0;
    }

    /**
     * @param string $name
     * @param string $version
     * @return array
     */
    protected static function getExtDetails(string $name, string $version): array
    {
        $table = 'tx_t3monitoring_domain_model_extension';

        $queryBuilderCoreExtensions = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table);
        $row = $queryBuilderCoreExtensions
            ->select('serialized_dependencies', 'typo3_min_version', 'typo3_max_version')
            ->from($table)
            ->where(
                $queryBuilderCoreExtensions->expr()->eq('name', $queryBuilderCoreExtensions->createNamedParameter($name)),
                $queryBuilderCoreExtensions->expr()->eq('version', $queryBuilderCoreExtensions->createNamedParameter($version))
            )
            ->setMaxResults(1)
            ->execute()->fetch();

        if ($row) {
            return $row;
        }
        return [];
    }
}
