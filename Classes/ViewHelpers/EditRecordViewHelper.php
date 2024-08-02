<?php
declare(strict_types=1);

namespace T3Monitor\T3monitoring\ViewHelpers;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Edit Record ViewHelper, see FormEngine logic
 */
class EditRecordViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('parameters', 'string', 'parameters', true);
    }

    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);

        $parameters = GeneralUtility::explodeUrl2Array($arguments['parameters']);

        /** @var ServerRequest $request */
        $request = $GLOBALS['TYPO3_REQUEST'];
        $queryParams = array_merge(
            $request->getQueryParams()['tx_t3monitoring_tools_t3monitoringt3monitor'] ?? [],
            $request->getParsedBody()['tx_t3monitoring_tools_t3monitoringt3monitor'] ?? []
        );

        $parameters['returnUrl'] = (string)$uriBuilder->buildUriFromRoute('t3monitoring', [
            'tx_t3monitoring_tools_t3monitoringt3monitor' => $queryParams
        ]);

        return $uriBuilder->buildUriFromRoute('record_edit', $parameters);
    }
}
