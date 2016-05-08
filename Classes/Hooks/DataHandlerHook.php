<?php
namespace T3Monitor\T3monitoring\Hooks;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Service\Import\ClientImport;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

class DataHandlerHook
{

    /**
     * @param string $status
     * @param string $table
     * @param string $recordUid
     * @param array $fields
     * @param DataHandler $parentObject
     */
    public function processDatamap_afterDatabaseOperations(
        $status,
        $table,
        $recordUid,
        array $fields,
        DataHandler $parentObject
    ) {
        if ($table === 'tx_t3monitoring_domain_model_client') {
            if (is_string($recordUid) && StringUtility::beginsWith($recordUid, 'NEW')) {
                $recordUid = $parentObject->substNEWwithIDs[$recordUid];
            }

            $clientRow = $this->getDatabase()->exec_SELECTgetSingleRow('*', $table, 'uid=' . (int)$recordUid);
            if ($clientRow) {
                $this->checkDomain($clientRow['domain']);
                $this->importClient($recordUid);
            }
        }
    }

    /**
     * @todo implement
     * @param string $domain
     */
    protected function checkDomain($domain)
    {
        return;
        $message = sprintf($GLOBALS['LANG']->sL('LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:client.domainNotPingAble'),
            htmlspecialchars($domain));
        $this->addFlashMessage($message, FlashMessage::WARNING);
    }

    /**
     * @param int $recordUid
     */
    protected function importClient($recordUid)
    {
        /** @var ClientImport $clientImport */
        $clientImport = GeneralUtility::makeInstance(ClientImport::class);
        $clientImport->run($recordUid);
    }

    /**
     * @param string $message
     * @param int $severity
     */
    protected function addFlashMessage($message, $severity = FlashMessage::INFO)
    {
        /** @var FlashMessage $flashMessage */
        $flashMessage = GeneralUtility::makeInstance(
            FlashMessage::class,
            $message,
            '',
            $severity);
        /** @var $flashMessageService FlashMessageService */
        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
        $flashMessageService->getMessageQueueByIdentifier()->addMessage($flashMessage);
    }

    /**
     * @return DatabaseConnection
     */
    protected function getDatabase()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
