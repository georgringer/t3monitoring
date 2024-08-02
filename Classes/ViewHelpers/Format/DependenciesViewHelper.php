<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\ViewHelpers\Format;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class DependenciesViewHelper extends AbstractViewHelper
{
    /** @var bool */
    protected $escapeOutput = false;

    /** @var bool */
    protected $escapeChildren = false;

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('dependencies', 'string', 'dependencies');
    }

    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext): string
    {
        $dependencies = $arguments['dependencies'] ?: $renderChildrenClosure();

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
            $output[$type] = '';
            foreach ($list as $item => $version) {
                $output[$type] .= sprintf('<tr><td>%s</td><td>%s</td></tr>', htmlspecialchars($item), htmlspecialchars($version));
            }
        }

        return '<table class="table table-white table-striped table-hover">' . implode(LF, $output) . '</table>';
    }
}
