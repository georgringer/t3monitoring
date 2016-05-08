<?php

namespace T3Monitor\T3monitoring\ViewHelpers;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\FormProtection\FormProtectionFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Fluid\Core\ViewHelper\Facets\CompilableInterface;

/**
 * Edit Record ViewHelper, see FormEngine logic
 */
class EditRecordViewHelper extends AbstractViewHelper implements CompilableInterface
{
    /**
     * Returns a URL to link to FormEngine
     *
     * @param string $parameters Is a set of GET params to send to FormEngine
     * @return string URL to FormEngine module + parameters
     * @see \TYPO3\CMS\Backend\Utility\BackendUtility::getModuleUrl()
     */
    public function render($parameters)
    {
        return static::renderStatic(
            [
                'parameters' => $parameters
            ],
            $this->buildRenderChildrenClosure(),
            $this->renderingContext
        );
    }

    /**
     * @param array $arguments
     * @param callable|\Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $parameters = GeneralUtility::explodeUrl2Array($arguments['parameters']);

        $parameters['returnUrl'] = 'index.php?M=tools_T3monitoringT3monitor&moduleToken='
            . FormProtectionFactory::get()->generateToken('moduleCall', 'tools_T3monitoringT3monitor')
            . GeneralUtility::implodeArrayForUrl(
                'tx_t3monitoring_tools_t3monitoringt3monitor',
                GeneralUtility::_GPmerged('tx_t3monitoring_tools_t3monitoringt3monitor'));
        return BackendUtility::getModuleUrl('record_edit', $parameters);
    }
}
