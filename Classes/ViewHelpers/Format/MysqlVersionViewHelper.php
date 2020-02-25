<?php

namespace T3Monitor\T3monitoring\ViewHelpers\Format;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class MysqlVersionViewHelper
 */
class MysqlVersionViewHelper extends AbstractViewHelper
{
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('version', 'string', 'state', false, '');
    }

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
