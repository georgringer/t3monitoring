<?php
declare(strict_types=1);

namespace T3Monitor\T3monitoring\Service;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DataIntegrity
{

    /**
     * Invoke after core import
     */
    public function invokeAfterCoreImport(): void
    {
        $this->usedCore();
    }

    /**
     * Invoke after client import
     */
    public function invokeAfterClientImport(): void
    {
        $this->usedCore();
        $this->usedExtensions();
    }

    /**
     * Invoke after extension import
     */
    public function invokeAfterExtensionImport(): void
    {
        $this->getLatestExtensionVersion();
        $this->getNextSecureExtensionVersion();
        $this->usedExtensions();
    }

    /**
     * Get latest extension version
     */
    protected function getLatestExtensionVersion(): void
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
                $eb->eq('insecure', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT)),
                $eb->gt('version_integer', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT)),
                $eb->eq('is_official', $queryBuilder->createNamedParameter(1, Connection::PARAM_INT))
            )
            ->groupBy('name', 'major_version', 'minor_version')
            ->executeQuery();

        $queryBuilder2 = $connection->createQueryBuilder();
        $eb2 = $queryBuilder2->expr();
        while ($row = $res->fetchAssociative()) {
            $highestBugFixRelease = $queryBuilder2
                ->select('version')
                ->from($table)
                ->where(
                    $eb2->eq('name', $queryBuilder2->createNamedParameter($row['name'])),
                    $eb2->eq('major_version', $queryBuilder2->createNamedParameter($row['major_version'], Connection::PARAM_INT)),
                    $eb2->eq('minor_version', $queryBuilder2->createNamedParameter($row['minor_version'], Connection::PARAM_INT))
                )
                ->orderBy('version_integer', 'desc')
                ->setMaxResults(1)
                ->executeQuery()
                ->fetchAssociative();

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
                $queryBuilder->expr()->eq('insecure', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT)),
                $queryBuilder->expr()->gt('version_integer', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT)),
                $queryBuilder->expr()->eq('is_official', $queryBuilder->createNamedParameter(1, Connection::PARAM_INT))
            )
            ->groupBy('name', 'major_version')
            ->executeQuery();

        $queryBuilder2 = $connection->createQueryBuilder();
        while ($row = $res->fetchAssociative()) {
            $highestBugFixRelease = $queryBuilder2
                ->select('version')
                ->from($table)
                ->where(
                    $queryBuilder->expr()->eq('name', $queryBuilder2->createNamedParameter($row['name'])),
                    $queryBuilder->expr()->eq('major_version', $queryBuilder2->createNamedParameter($row['major_version'], Connection::PARAM_INT))
                )
                ->orderBy('version_integer', 'desc')
                ->setMaxResults(1)
                ->executeQuery()
                ->fetchAssociative();

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
            ->executeQuery();

        $queryBuilder = $connection->createQueryBuilder();
        while ($row = $queryResult->fetchAssociative()) {
            $queryBuilder->update($table)
                ->set('last_major_release', $row['version'])
                ->where($queryBuilder->expr()->eq('name', $queryBuilder->createNamedParameter($row['name'])))
                ->executeStatement();
        }

        // set is_latest = 0 for extension versions that are not last_major_release
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->update($table)
            ->set('is_latest', 0)
            ->where('version != last_major_release')
            ->executeStatement();

        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->update($table)
            ->set('is_latest', 1)
            ->where('version=last_major_release')
            ->executeStatement();
    }

    /**
     * Get next secure extension version
     */
    protected function getNextSecureExtensionVersion(): void
    {
        $table = 'tx_t3monitoring_domain_model_extension';

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $insecureExtensions = $queryBuilder
            ->select('uid', 'name', 'version_integer')
            ->from($table)
            ->where($queryBuilder->expr()->eq('insecure', $queryBuilder->createNamedParameter(1, Connection::PARAM_INT)))
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($insecureExtensions as $row) {
            $queryBuilder2 = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable($table);
            $nextSecureVersion = $queryBuilder2
                ->select('uid', 'version')
                ->from($table)
                ->where(
                    $queryBuilder->expr()->eq('insecure', $queryBuilder2->createNamedParameter(0, Connection::PARAM_INT)),
                    $queryBuilder->expr()->eq('name', $queryBuilder2->createNamedParameter($row['name'])),
                    $queryBuilder->expr()->gt('version_integer', $queryBuilder2->createNamedParameter($row['version_integer'], Connection::PARAM_INT))
                )
                ->setMaxResults(1)
                ->executeQuery()
                ->fetchAssociative();

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
    protected function usedCore(): void
    {
        $table = 'tx_t3monitoring_domain_model_core';
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $rows = $queryBuilder
            ->select('tx_t3monitoring_domain_model_core.uid')
            ->from($table)
            ->innerJoin(
                'tx_t3monitoring_domain_model_core',
                'tx_t3monitoring_domain_model_client',
                'tx_t3monitoring_domain_model_client',
                $queryBuilder->expr()->eq('tx_t3monitoring_domain_model_core.uid', $queryBuilder->quoteIdentifier('tx_t3monitoring_domain_model_client.core'))
            )
            ->executeQuery()
            ->fetchAllAssociative();
        $coreRows = [];
        foreach ($rows as $row) {
            $coreRows[$row['uid']] = $row;
        }

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table);
        $qb = $connection->createQueryBuilder();
        $qb->update($table)
            ->set('is_used', 0)
            ->executeStatement();
        if (!empty($coreRows)) {
            foreach ($coreRows as $id => $row) {
                $qb->where('uid = ' . $id);
                $qb->set('is_used', 1)->executeStatement();
            }
        }
    }

    /**
     * Used extensions
     */
    protected function usedExtensions(): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_t3monitoring_domain_model_client');
        $queryBuilder = $connection->createQueryBuilder();
        $clients = $queryBuilder
            ->select('uid')
            ->from('tx_t3monitoring_domain_model_client')
            ->executeQuery()
            ->fetchAllAssociative();

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
                    $queryBuilder->expr()->eq('is_official', $queryBuilder->createNamedParameter(1, Connection::PARAM_INT)),
                    $queryBuilder->expr()->eq('insecure', $queryBuilder->createNamedParameter(1, Connection::PARAM_INT)),
                    $queryBuilder->expr()->eq('tx_t3monitoring_client_extension_mm.uid_local', $queryBuilder->createNamedParameter($client['uid'], Connection::PARAM_INT))
                )->executeQuery()->fetchFirstColumn();

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
                    $queryBuilder2->expr()->eq('is_official', $queryBuilder2->createNamedParameter(1, Connection::PARAM_INT)),
                    $queryBuilder2->expr()->eq('insecure', $queryBuilder2->createNamedParameter(0, Connection::PARAM_INT)),
                    $queryBuilder2->expr()->eq('is_latest', $queryBuilder2->createNamedParameter(0, Connection::PARAM_INT)),
                    $queryBuilder2->expr()->eq('tx_t3monitoring_client_extension_mm.uid_local', $queryBuilder2->createNamedParameter($client['uid'], Connection::PARAM_INT))
                )->executeQuery()->fetchFirstColumn();

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
