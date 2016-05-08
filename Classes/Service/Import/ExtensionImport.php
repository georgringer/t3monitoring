<?php

namespace T3Monitor\T3monitoring\Service\Import;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Service\DataIntegrity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extensionmanager\Utility\Repository\Helper;

class ExtensionImport extends BaseImport
{

    // Release date of 4.5.0
    const MIN_DATE = '26.1.2011';

    public function run()
    {
        $updateRequired = $this->updateExtensionList();
        if ($updateRequired) {
            $this->insertExtensionsInCustomTable();
        }
        /** @var DataIntegrity $dataIntegrity */
        $dataIntegrity = GeneralUtility::makeInstance(DataIntegrity::class);
        $dataIntegrity->invokeAfterExtensionImport();
        $this->setImportTime('extension');
    }

    protected function insertExtensionsInCustomTable()
    {
        $table = 'tx_t3monitoring_domain_model_extension';
        $res = $this->getDatabaseConnection()->exec_SELECTquery(
            'extension_key,state,review_state,version,title,category,description,last_updated,author_name,update_comment,integer_version,state,current_version,serialized_dependencies',
            'tx_extensionmanager_domain_model_extension',
            'last_updated >' . strtotime(self::MIN_DATE)
        );

        while ($row = $this->getDatabaseConnection()->sql_fetch_assoc($res)) {
            $exists = $this->getDatabaseConnection()->exec_SELECTgetSingleRow(
                'uid,version,name',
                $table,
                'version=' . $this->getDatabaseConnection()->fullQuoteStr($row['version'], $table)
                . ' AND name=' . $this->getDatabaseConnection()->fullQuoteStr($row['extension_key'], $table)
            );
            $versionSplit = explode('.', $row['version'], 3);

            $fields = [
                'pid' => $this->emConfiguration->getPid(),
                'is_official' => 1,
                'insecure' => ((int)$row['review_state'] === -1 ? 1 : 0),
                'name' => $row['extension_key'],
                'version' => $row['version'],
                'version_integer' => $row['integer_version'],
                'major_version' => (int)$versionSplit[0],
                'minor_version' => (int)$versionSplit[1],
                'last_updated' => date('Y-m-d H:i:s', $row['last_updated']),
                'update_comment' => $row['update_comment'],
                'author_name' => $row['author_name'],
                'title' => $row['title'],
                'description' => $row['description'],
                'state' => $row['state'],
                'category' => $row['category'],
                'serialized_dependencies' => (string)$row['serialized_dependencies'],
                'tstamp' => $GLOBALS['EXEC_TIME'],
            ];

            // update
            if (is_array($exists)) {
                $this->getDatabaseConnection()->exec_UPDATEquery($table, 'uid=' . (int)$exists['uid'], $fields);
            } else {
                // insert
                $fields['crdate'] = $GLOBALS['EXEC_TIME'];
                $this->getDatabaseConnection()->exec_INSERTquery($table, $fields);
            }
        }
    }

    /**
     * @return bool TRUE if the extension list was successfully update, FALSE if no update necessary
     */
    protected function updateExtensionList()
    {
        /** @var Helper $extensionRepository */
        $extensionRepository = GeneralUtility::makeInstance(Helper::class);
        return $extensionRepository->updateExtList();
    }
}
