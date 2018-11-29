<?php

namespace T3Monitor\T3monitoring\Service\Import;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use InvalidArgumentException;
use T3Monitor\T3monitoring\Service\DataIntegrity;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extensionmanager\Exception\ExtensionManagerException;
use TYPO3\CMS\Extensionmanager\Utility\Repository\Helper;

/**
 * Class ExtensionImport
 */
class ExtensionImport extends BaseImport
{

    // Release date of 4.5.0
    const MIN_DATE = '26.1.2011';

    /**
     * Run extension import
     *
     * @throws InvalidArgumentException
     * @throws ExtensionManagerException
     */
    public function run()
    {
        $updateRequired = $this->updateExtensionList();
        if ($updateRequired) {
            $this->insertExtensionsInCustomTable();
        }
        $dataIntegrity = GeneralUtility::makeInstance(DataIntegrity::class);
        $dataIntegrity->invokeAfterExtensionImport();
        $this->setImportTime('extension');
    }

    /**
     * Insert Extension in custom table
     */
    protected function insertExtensionsInCustomTable()
    {
        $table = 'tx_t3monitoring_domain_model_extension';
        $queryBuilderCoreExtensions = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_extensionmanager_domain_model_extension');
        $res = $queryBuilderCoreExtensions
            ->select('extension_key', 'state', 'review_state', 'version', 'title', 'category', 'description', 'last_updated', 'author_name', 'update_comment', 'integer_version', 'current_version', 'serialized_dependencies')
            ->from('tx_extensionmanager_domain_model_extension')
            ->where(
                $queryBuilderCoreExtensions->expr()->gt('last_updated', $queryBuilderCoreExtensions->createNamedParameter(strtotime(self::MIN_DATE)))
            )->execute();

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table);
        while ($row = $res->fetch()) {
            $versionSplit = explode('.', $row['version'], 3);

            $fields = [
                'pid' => $this->emConfiguration->getPid(),
                'is_official' => 1,
                'insecure' => (int)$row['review_state'] === -1 ? 1 : 0,
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

            $exists = $queryBuilder
                ->select('uid', 'version', 'name')
                ->from($table)
                ->where(
                    $queryBuilder->expr()->eq('version', $queryBuilder->createNamedParameter($row['version'])),
                    $queryBuilder->expr()->eq('name', $queryBuilder->createNamedParameter($row['extension_key']))
                )->execute()->fetch();

            $connection = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable($table);

            // update
            if (is_array($exists) && !empty($exists)) {
                $connection->update(
                    $table,
                    $fields,
                    [
                        'uid' => (int)$exists['uid']
                    ]
                );
            } else {
                // insert
                $fields['crdate'] = $GLOBALS['EXEC_TIME'];
                $connection->insert($table, $fields);
            }
        }
    }

    /**
     * @return bool TRUE if the extension list was successfully update, FALSE if no update necessary
     * @throws ExtensionManagerException
     */
    protected function updateExtensionList(): bool
    {
        $extensionRepository = GeneralUtility::makeInstance(Helper::class);
        return $extensionRepository->updateExtList();
    }
}
