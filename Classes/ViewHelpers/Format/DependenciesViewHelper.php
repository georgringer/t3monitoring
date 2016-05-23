<?php
namespace T3Monitor\T3monitoring\ViewHelpers\Format;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class DependenciesViewHelper
 */
class DependenciesViewHelper extends AbstractViewHelper
{

    /**
     * For CMS 7
     * 
     * @var bool
     * */
    protected $escapingInterceptorEnabled = false;

    /**
     * For CMS 8
     * 
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * For CMS 8
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * @param string $dependencies
     * @return string
     */
    public function render($dependencies = '')
    {
        $dependencies = $dependencies ?: $this->renderChildren();

        if (empty($dependencies)) {
            return '';
        }

        $dependencies = unserialize($dependencies);
        if (!is_array($dependencies)) {
            return '';
        }
        $output = [];
        foreach ($dependencies as $type => $list) {
            $output[] = '<tr><th colspan="2">' . htmlspecialchars(ucfirst($type)) . '</th></tr>';
            foreach ($list as $item => $version) {
                $output[$type] .= sprintf('<tr><td>%s</td><td>%s</td></tr>', htmlspecialchars($item), htmlspecialchars($version));
            }
        }

        return '<table class="table table-white table-striped table-hover">' . implode(LF, $output) . '</table>';
    }
}
