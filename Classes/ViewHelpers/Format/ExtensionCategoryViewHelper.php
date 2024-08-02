<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\ViewHelpers\Format;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Extension;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class ExtensionCategoryViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('category', 'int', 'category', false, 0);
    }

    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $category = $arguments['category'] ?: $renderChildrenClosure();
        $categoryString = '';
        if (isset(Extension::$defaultCategories[$category])) {
            $categoryString = Extension::$defaultCategories[$category];
        }
        return $categoryString;
    }
}
