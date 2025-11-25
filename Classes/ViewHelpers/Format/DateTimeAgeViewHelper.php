<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\ViewHelpers\Format;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class DateTimeAgeViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('date', \DateTime::class, 'date', true);
    }

    public function render(): string
    {
        /** @var \DateTime $date */
        $date = $this->arguments['date'];
        return BackendUtility::dateTimeAge($date->getTimestamp(), 1, 'date');
    }
}
