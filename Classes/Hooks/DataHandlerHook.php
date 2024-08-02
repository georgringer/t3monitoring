<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\Hooks;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Service\Import\ClientImport;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DataHandlerHook
{
    public function processDatamap_afterDatabaseOperations(
        string $status,
        string $table,
        string|int $recordUid,
        array $fields,
        DataHandler $parentObject
    ): void {
        if ($table === 'tx_t3monitoring_domain_model_client') {
            if (is_string($recordUid) && str_starts_with($recordUid, 'NEW')) {
                $recordUid = $parentObject->substNEWwithIDs[$recordUid];
            }

            $recordUid = (int)$recordUid;
            $clientRow = BackendUtility::getRecord($table, $recordUid);
            if ($clientRow && $clientRow['exclude_from_import'] !== 1) {
                $this->checkDomain($clientRow['domain']);
                $this->importClient($recordUid);
            }
        }
    }

    /**
     * @todo implement
     * @param string $domain
     */
    protected function checkDomain(string $domain): void
    {
        return;
        $message = sprintf(
            $this->getLanguageService()->sL('LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:client.domainNotPingAble'),
            $domain
        );
        $this->addFlashMessage($message, AbstractMessage::WARNING);
    }

    protected function importClient(int $recordUid): void
    {
        /** @var ClientImport $clientImport */
        $clientImport = GeneralUtility::makeInstance(ClientImport::class);
        $clientImport->run($recordUid);
    }

    protected function addFlashMessage(string $message, int $severity = AbstractMessage::INFO): void
    {
        $flashMessage = GeneralUtility::makeInstance(
            FlashMessage::class,
            $message,
            '',
            $severity
        );
        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
        $flashMessageService->getMessageQueueByIdentifier()->addMessage($flashMessage);
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
