<?php

namespace T3Monitor\T3monitoring\ViewHelpers\Format;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

class MysqlVersionViewHelper extends AbstractViewHelper
{

    /**
     * @param string $version
     * @return string
     */
    public function render($version = '')
    {
        $version = $version ?: $this->renderChildren();

        $versionString = str_pad($version, 5, '0', STR_PAD_LEFT);
        $parts = array(
            substr($versionString, 0, 1),
            substr($versionString, 1, 2),
            substr($versionString, 3, 5)
        );

        return (int)$parts[0] . '.' . (int)$parts[1] . '.' . (int)$parts[2];
    }
}
