<?php

namespace T3Monitor\T3monitoring\Service;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class DataIntegrity
 */
class DataIntegrity
{

    /**
     * Invoke after core import
     */
    public function invokeAfterCoreImport()
    {
        $this->usedCore();
    }

    /**
     * Invoke after client import
     */
    public function invokeAfterClientImport()
    {
        $this->usedCore();
        $this->usedExtensions();
    }

    /**
     * Invoke after extension import
     */
    public function invokeAfterExtensionImport()
    {
        $this->getLatestExtensionVersion();
        $this->getNextSecureExtensionVersion();
        $this->usedExtensions();
    }

    /**
     * Get latest extension version
     */
    protected function getLatestExtensionVersion()
    {
        $table = 'tx_t3monitoring_domain_model_extension';

        // Patch release
        $queryResult = $this->getDatabaseConnection()->sql_query('
            SELECT name,major_version as major,minor_version as minor
            FROM tx_t3monitoring_domain_model_extension
            WHERE insecure = 0 AND version_integer > 0 AND is_official = 1
            GROUP BY name,major,minor'
        );

        while ($row = $this->getDatabaseConnection()->sql_fetch_assoc($queryResult)) {
            $where = 'name=' . $this->getDatabaseConnection()->fullQuoteStr($row['name'],
                    $table) . ' AND major_version=' . $row['major'] . ' AND minor_version=' . $row['minor'];
            $highestBugFixRelease = $this->getDatabaseConnection()->exec_SELECTgetSingleRow(
                'version',
                $table,
                $where,
                '',
                'version_integer desc'
            );
            if (is_array($highestBugFixRelease)) {
                $this->getDatabaseConnection()->exec_UPDATEquery($table, $where, [
                    'last_bugfix_release' => $highestBugFixRelease['version']
                ]);
            }
        }
        $this->getDatabaseConnection()->sql_free_result($queryResult);

        // Minor release
        $queryResult = $this->getDatabaseConnection()->sql_query('
            SELECT name,major_version as major
            FROM tx_t3monitoring_domain_model_extension
            WHERE insecure = 0 AND version_integer > 0 AND is_official = 1
            GROUP BY name,major'
        );

        while ($row = $this->getDatabaseConnection()->sql_fetch_assoc($queryResult)) {
            $where = 'name=' . $this->getDatabaseConnection()->fullQuoteStr($row['name'],
                    $table) . ' AND major_version=' . $row['major'];
            $highestBugFixRelease = $this->getDatabaseConnection()->exec_SELECTgetSingleRow(
                'version',
                $table,
                $where,
                '',
                'version_integer desc'
            );
            if (is_array($highestBugFixRelease)) {
                $this->getDatabaseConnection()->exec_UPDATEquery($table, $where, [
                    'last_minor_release' => $highestBugFixRelease['version']
                ]);
            }
        }
        $this->getDatabaseConnection()->sql_free_result($queryResult);

        // Major release
        $queryResult = $this->getDatabaseConnection()->sql_query(
            'SELECT a.version,a.name ' .
            'FROM ' . $table . ' a ' .
            'LEFT JOIN ' . $table . ' b ON a.name = b.name AND a.version_integer < b.version_integer ' .
            'WHERE b.name IS NULL ' .
            'ORDER BY a.uid'
        );

        while ($row = $this->getDatabaseConnection()->sql_fetch_assoc($queryResult)) {
            $where = 'name=' . $this->getDatabaseConnection()->fullQuoteStr($row['name'], $table);
            $this->getDatabaseConnection()->exec_UPDATEquery($table, $where, [
                'last_major_release' => $row['version']
            ]);
        }
        $this->getDatabaseConnection()->sql_free_result($queryResult);

        // mark latest version
        $this->getDatabaseConnection()->sql_query('
            UPDATE ' . $table . '
            SET is_latest=1 WHERE version=last_major_release
        ');
    }

    /**
     * Get next secure extension version
     */
    protected function getNextSecureExtensionVersion()
    {
        $table = 'tx_t3monitoring_domain_model_extension';
        $insecureExtensions = $this->getDatabaseConnection()->exec_SELECTgetRows(
            'uid,name,version_integer',
            $table,
            'insecure=1');
        foreach ($insecureExtensions as $row) {
            $where = sprintf(
                'insecure=0 AND name=%s AND version_integer>%s',
                $this->getDatabaseConnection()->fullQuoteStr($row['name'], $table),
                $row['version_integer']
            );
            $nextSecureVersion = $this->getDatabaseConnection()->exec_SELECTgetSingleRow(
                'uid,version',
                $table, $where);

            if (is_array($nextSecureVersion)) {
                $this->getDatabaseConnection()->exec_UPDATEquery(
                    $table,
                    'uid=' . $row['uid'],
                    ['next_secure_version' => $nextSecureVersion['version']]
                );
            }
        }
    }

    /**
     * Used core
     */
    protected function usedCore()
    {
        $table = 'tx_t3monitoring_domain_model_core';
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table);
        $rows = $queryBuilder
            ->select('tx_t3monitoring_domain_model_core.uid')
            ->from($table)
            ->leftJoin(
                'tx_t3monitoring_domain_model_core',
                'tx_t3monitoring_domain_model_client',
                'tx_t3monitoring_domain_model_client',
                $queryBuilder->expr()->eq('tx_t3monitoring_domain_model_core.uid', $queryBuilder->quoteIdentifier('tx_t3monitoring_domain_model_client.core'))
            )
            ->execute()
            ->fetchAll();
        $coreRows = [];
        foreach ($rows as $row) {
            $coreRows[$row['uid']] = $row;
        }

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable($table);
        $connection->update($table, ['is_used' => 0], []);
        if (!empty($coreRows)) {
            $connection->update($table, ['is_used' => 1], []);
            foreach ($coreRows as $id => $row) {
                $connection->update($table, ['is_used' => 1], ['uid' => $id]);
            }
        }
    }

    /**
     * Used extensions
     */
    protected function usedExtensions()
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_t3monitoring_domain_model_client');
        $clients = $queryBuilder
            ->select('uid')
            ->from('tx_t3monitoring_domain_model_client')
            ->execute()
            ->fetchAll();

        foreach ($clients as $client) {
            $queryBuilder = $this->getQueryBuilderFor('tx_t3monitoring_client_extension_mm');

            $countInsecure = $queryBuilder
                ->count('uid')
                ->from('tx_t3monitoring_client_extension_mm')
                ->leftJoin(
                    'tx_t3monitoring_client_extension_mm',
                    'tx_t3monitoring_domain_model_extension',
                    'tx_t3monitoring_domain_model_extension',
                    $queryBuilder->expr()->eq('tx_t3monitoring_client_extension_mm.uid_foreign', $queryBuilder->quoteIdentifier('tx_t3monitoring_domain_model_extension.uid'))
                )
                ->where(
                    $queryBuilder->expr()->eq('is_official', $queryBuilder->createNamedParameter(1, \PDO::PARAM_INT)),
                    $queryBuilder->expr()->eq('insecure', $queryBuilder->createNamedParameter(1, \PDO::PARAM_INT)),
                    $queryBuilder->expr()->eq('tx_t3monitoring_client_extension_mm.uid_local', $queryBuilder->createNamedParameter($client['uid'], \PDO::PARAM_INT))
                )->execute()->fetchColumn(0);

            // count outdated extensions
            $queryBuilder = $this->getQueryBuilderFor('tx_t3monitoring_client_extension_mm');
            $countOutdated = $queryBuilder
                ->count('uid')
                ->from('tx_t3monitoring_client_extension_mm')
                ->leftJoin(
                    'tx_t3monitoring_client_extension_mm',
                    'tx_t3monitoring_domain_model_extension',
                    'tx_t3monitoring_domain_model_extension',
                    $queryBuilder->expr()->eq('tx_t3monitoring_client_extension_mm.uid_foreign', $queryBuilder->quoteIdentifier('tx_t3monitoring_domain_model_extension.uid'))
                )
                ->where(
                    $queryBuilder->expr()->eq('is_official', $queryBuilder->createNamedParameter(1, \PDO::PARAM_INT)),
                    $queryBuilder->expr()->eq('insecure', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                    $queryBuilder->expr()->eq('is_latest', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                    $queryBuilder->expr()->eq('tx_t3monitoring_client_extension_mm.uid_local', $queryBuilder->createNamedParameter($client['uid'], \PDO::PARAM_INT))
                )->execute()->fetchColumn(0);

            // update client
            $connection = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable('tx_t3monitoring_domain_model_client');
            $connection->update(
                'tx_t3monitoring_domain_model_client',
                [
                    'insecure_extensions' => $countInsecure,
                    'outdated_extensions' => $countOutdated
                ],
                [
                    'uid' => $client['uid']
                ]
            );
        }

        // Used extensions
        $this->getDatabaseConnection()->sql_query('
            UPDATE tx_t3monitoring_domain_model_extension
            SET is_used=1
            WHERE uid IN (
              SELECT uid_foreign FROM tx_t3monitoring_client_extension_mm
            );'
        );
    }

    protected function getQueryBuilderFor(string $table): QueryBuilder
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table);
    }

    /**
     * @return DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
