<?php

namespace T3Monitor\T3monitoring\ViewHelpers\Format;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

class ExtensionCategoryViewHelper extends AbstractViewHelper
{

    /**
     * Contains default categories.
     *
     * @var array
     */
    protected static $defaultCategories = array(
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
    );

    /**
     * @param int $category
     * @return string
     */
    public function render($category = 0)
    {
        $category = $category ?: $this->renderChildren();
        $categoryString = '';
        if (isset(self::$defaultCategories[$category])) {
            $categoryString = self::$defaultCategories[$category];
        }
        return $categoryString;
    }
}
