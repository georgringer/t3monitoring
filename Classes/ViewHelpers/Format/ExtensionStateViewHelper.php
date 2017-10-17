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
 * Class ExtensionStateViewHelper
 */
class ExtensionStateViewHelper extends AbstractViewHelper implements CompilableInterface
{
    use CompileWithRenderStatic;

    public function initializeArguments()
    {
        $this->registerArgument('state', 'int', 'state', true);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $state = $arguments['state'] ?: $renderChildrenClosure();
        $stateString = '';
        if (isset(Extension::$defaultStates[$state])) {
            $stateString = Extension::$defaultStates[$state];
        }
        return $stateString;
    }
}
