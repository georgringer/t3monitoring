<?php
namespace T3Monitor\T3monitoring\ViewHelpers;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class AdditionalExtensionDataViewHelper
 */
class AdditionalExtensionDataViewHelper extends AbstractViewHelper
{
    /**
     * For CMS 8
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * @param int $client client id
     * @param int $extension extension id
     * @param string $as
     * @return string
     */
    public function render($client, $extension, $as)
    {
        $row = $this->getDatabaseConnection()->exec_SELECTgetSingleRow(
            'is_loaded,state,title',
            'tx_t3monitoring_client_extension_mm',
            sprintf('uid_local=%s AND uid_foreign=%s', (int)$client, (int)$extension));

        $this->templateVariableContainer->add($as, $row);
        $output = $this->renderChildren();
        $this->templateVariableContainer->remove($as);
        return $output;
    }

    /**
     * @return DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
