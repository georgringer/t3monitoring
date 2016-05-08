<?php

namespace T3Monitor\T3monitoring\ViewHelpers\Format;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Extension;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

class ExtensionStateViewHelper extends AbstractViewHelper
{

    /**
     * @param int $state
     * @return string
     */
    public function render($state = 0)
    {
        $state = $state ?: $this->renderChildren();
        $stateString = '';
        if (isset(Extension::$defaultStates[$state])) {
            $stateString = Extension::$defaultStates[$state];
        }
        return $stateString;
    }
}
