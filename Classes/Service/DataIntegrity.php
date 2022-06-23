<?php

namespace T3Monitor\T3monitoring\Service;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Database\ConnectionPool;
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
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table);

        $queryBuilder = $connection->createQueryBuilder();
        $eb = $queryBuilder->expr();
        $res = $queryBuilder
            ->select('name', 'major_version', 'minor_version')
            ->from($table)
            ->where(
                $eb->eq('insecure', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                $eb->gt('version_integer', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                $eb->eq('is_official', $queryBuilder->createNamedParameter(1, \PDO::PARAM_INT))
            )
            ->groupBy('name', 'major_version', 'minor_version')
            ->execute();

        $queryBuilder2 = $connection->createQueryBuilder();
        $eb2 = $queryBuilder2->expr();
        while ($row = $res->fetch()) {
            $highestBugFixRelease = $queryBuilder2
                ->select('version')
                ->from($table)
                ->where(
                    $eb2->eq('name', $queryBuilder2->createNamedParameter($row['name'])),
                    $eb2->eq('major_version', $queryBuilder2->createNamedParameter($row['major_version'], \PDO::PARAM_INT)),
                    $eb2->eq('minor_version', $queryBuilder2->createNamedParameter($row['minor_version'], \PDO::PARAM_INT))
                )
                ->orderBy('version_integer', 'desc')
                ->setMaxResults(1)
                ->execute()
                ->fetch();

            if (is_array($highestBugFixRelease)) {
                $connection = GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getConnectionForTable($table);
                $connection->update(
                    $table,
                    ['last_bugfix_release' => $highestBugFixRelease['version']],
                    [
                        'name' => $row['name'],
                        'major_version' => $row['major_version'],
                        'minor_version' => $row['minor_version'],
                    ]);
            }
        }

        // Minor release
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $res = $queryBuilder
            ->select('name', 'major_version')
            ->from($table)
            ->where(
                $queryBuilder->expr()->eq('insecure', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                $queryBuilder->expr()->gt('version_integer', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                $queryBuilder->expr()->eq('is_official', $queryBuilder->createNamedParameter(1, \PDO::PARAM_INT))
            )
            ->groupBy('name', 'major_version')
            ->execute();

        $queryBuilder2 = $connection->createQueryBuilder();
        while ($row = $res->fetch()) {
            $highestBugFixRelease = $queryBuilder2
                ->select('version')
                ->from($table)
                ->where(
                    $queryBuilder->expr()->eq('name', $queryBuilder2->createNamedParameter($row['name'])),
                    $queryBuilder->expr()->eq('major_version', $queryBuilder2->createNamedParameter($row['major_version'], \PDO::PARAM_INT))
                )
                ->orderBy('version_integer', 'desc')
                ->setMaxResults(1)
                ->execute()
                ->fetch();

            if (is_array($highestBugFixRelease)) {
                $connection->update(
                    $table,
                    ['last_minor_release' => $highestBugFixRelease['version']],
                    [
                        'name' => $row['name'],
                        'major_version' => $row['major_version']
                    ]);
            }
        }

        // Major release
        $queryBuilder = $connection->createQueryBuilder();
        $queryResult = $queryBuilder
            ->select('a.version', 'a.name')
            ->from($table, 'a')
            ->leftJoin('a', $table, 'b', 'a.name = b.name AND a.version_integer < b.version_integer')
            ->where($queryBuilder->expr()->isNull('b.name'))
            ->orderBy('a.uid')
            ->execute();

        $queryBuilder = $connection->createQueryBuilder();
        while ($row = $queryResult->fetch()) {
            $queryBuilder->update($table)
                ->set('last_major_release', $row['version'])
                ->where($queryBuilder->expr()->eq('name', $queryBuilder->createNamedParameter($row['name'])))
                ->execute();
        }

        // set is_latest = 0 for extension versions that are not last_major_release
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->update($table)
            ->set('is_latest', 0)
            ->where('version != last_major_release')
            ->execute();

        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->update($table)
            ->set('is_latest', 1)
            ->where('version=last_major_release')
            ->execute();
    }

    /**
     * Get next secure extension version
     */
    protected function getNextSecureExtensionVersion()
    {
        $table = 'tx_t3monitoring_domain_model_extension';

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $insecureExtensions = $queryBuilder
            ->select('uid', 'name', 'version_integer')
            ->from($table)
            ->where($queryBuilder->expr()->eq('insecure', $queryBuilder->createNamedParameter(1, \PDO::PARAM_INT)))
            ->execute()
            ->fetchAll();

        foreach ($insecureExtensions as $row) {
            $queryBuilder2 = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable($table);
            $nextSecureVersion = $queryBuilder2
                ->select('uid', 'version')
                ->from($table)
                ->where(
                    $queryBuilder->expr()->eq('insecure', $queryBuilder2->createNamedParameter(0, \PDO::PARAM_INT)),
                    $queryBuilder->expr()->eq('name', $queryBuilder2->createNamedParameter($row['name'])),
                    $queryBuilder->expr()->gt('version_integer', $queryBuilder2->createNamedParameter($row['version_integer'], \PDO::PARAM_INT))
                )
                ->setMaxResults(1)
                ->execute()
                ->fetch();

            if (is_array($nextSecureVersion)) {
                $connection = GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getConnectionForTable($table);
                $connection->update($table, ['next_secure_version' => $nextSecureVersion['version']], ['uid' => $row['uid']]);
            }
        }
    }

    /**
     * Used core
     */
    protected function usedCore()
    {
        $table = 'tx_t3monitoring_domain_model_core';
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
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

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table);
        $qb = $connection->createQueryBuilder();
        $qb->update($table)
            ->set('is_used', 0)
            ->execute();
        if (!empty($coreRows)) {
            $qb->set('is_used', 1)->execute();
            foreach ($coreRows as $id => $row) {
                $qb->where('uid = ' . $id);
                $qb->set('is_used', 1)->execute();
            }
        }
    }

    /**
     * Used extensions
     */
    protected function usedExtensions()
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_t3monitoring_domain_model_client');
        $queryBuilder = $connection->createQueryBuilder();
        $clients = $queryBuilder
            ->select('uid')
            ->from('tx_t3monitoring_domain_model_client')
            ->execute()
            ->fetchAll();

        foreach ($clients as $client) {
            $queryBuilder = $connection->createQueryBuilder();
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
            $queryBuilder2 = $connection->createQueryBuilder();
            $countOutdated = $queryBuilder2
                ->count('uid')
                ->from('tx_t3monitoring_client_extension_mm')
                ->leftJoin(
                    'tx_t3monitoring_client_extension_mm',
                    'tx_t3monitoring_domain_model_extension',
                    'tx_t3monitoring_domain_model_extension',
                    $queryBuilder2->expr()->eq('tx_t3monitoring_client_extension_mm.uid_foreign', $queryBuilder2->quoteIdentifier('tx_t3monitoring_domain_model_extension.uid'))
                )
                ->where(
                    $queryBuilder2->expr()->eq('is_official', $queryBuilder2->createNamedParameter(1, \PDO::PARAM_INT)),
                    $queryBuilder2->expr()->eq('insecure', $queryBuilder2->createNamedParameter(0, \PDO::PARAM_INT)),
                    $queryBuilder2->expr()->eq('is_latest', $queryBuilder2->createNamedParameter(0, \PDO::PARAM_INT)),
                    $queryBuilder2->expr()->eq('tx_t3monitoring_client_extension_mm.uid_local', $queryBuilder2->createNamedParameter($client['uid'], \PDO::PARAM_INT))
                )->execute()->fetchColumn(0);

            // update client
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
        $queryBuilder = $connection->createQueryBuilder();
        $subSelect = $queryBuilder->select('uid_foreign')->from('tx_t3monitoring_client_extension_mm')->getSQL();

        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder
            ->update('tx_t3monitoring_domain_model_extension')
            ->set('is_used', 1)
            ->where($queryBuilder->expr()->in('uid', $subSelect));
    }
}
