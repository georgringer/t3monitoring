<?php
namespace T3Monitor\T3monitoring\ViewHelpers\Format;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DateTime;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class DateTimeAgeViewHelper
 */
class DateTimeAgeViewHelper extends AbstractViewHelper
{

    /**
     * @param DateTime $date
     * @return string
     */
    public function render(DateTime $date = null)
    {
        if ($date === null) {
            return '';
        }
        return BackendUtility::dateTimeAge($date->getTimestamp(), 1, 'date');
    }
}
