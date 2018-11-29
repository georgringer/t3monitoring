<?php

namespace T3Monitor\T3monitoring\ViewHelpers\Format;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Fluid\Core\ViewHelper\Facets\CompilableInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Class ExtensionCategoryViewHelper
 */
class ExtensionCategoryViewHelper extends AbstractViewHelper implements CompilableInterface
{
    use CompileWithRenderStatic;

    public function initializeArguments()
    {
        $this->registerArgument('category', 'int', 'category', false, 0);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $category = $arguments['category'] ?: $renderChildrenClosure();
        $categoryString = '';
        if (isset(self::$defaultCategories[$category])) {
            $categoryString = self::$defaultCategories[$category];
        }
        return $categoryString;
    }

    /**
     * Contains default categories.
     *
     * @var array
     */
    private static $defaultCategories = [
        0 => 'be',
        1 => 'module',
        2 => 'fe',
        3 => 'plugin',
        4 => 'misc',
        5 => 'services',
        6 => 'templates',
        8 => 'doc',
        9 => 'example',
        10 => 'distribution'
    ];
}
