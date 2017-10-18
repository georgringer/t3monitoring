<?php

namespace T3Monitor\T3monitoring\ViewHelpers\Format;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Extension;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Fluid\Core\ViewHelper\Facets\CompilableInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Class MysqlVersionViewHelper
 */
class MysqlVersionViewHelper extends AbstractViewHelper implements CompilableInterface
{
    use CompileWithRenderStatic;

    public function initializeArguments()
    {
        $this->registerArgument('version', 'string', 'state', false, '');
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $version = $arguments['version'] ?: $renderChildrenClosure();
        $versionString = str_pad($version, 5, '0', STR_PAD_LEFT);
        $parts = [
            $versionString[0],
            substr($versionString, 1, 2),
            substr($versionString, 3, 5)
        ];

        return (int)$parts[0] . '.' . (int)$parts[1] . '.' . (int)$parts[2];
    }
}
