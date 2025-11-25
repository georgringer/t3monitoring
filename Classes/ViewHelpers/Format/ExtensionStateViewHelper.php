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
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class ExtensionStateViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('state', 'int', 'state', false, 0);
    }

    public function render(): string
    {
        $state = $this->arguments['state'] ?: $this->renderChildren();
        $stateString = '';
        if (isset(Extension::$defaultStates[$state])) {
            $stateString = Extension::$defaultStates[$state];
        }
        return $stateString;
    }
}
