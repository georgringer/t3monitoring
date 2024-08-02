<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\Domain\Repository;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class StatisticRepository extends BaseRepository
{
    public function getUsedCoreVersionCount(): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_t3monitoring_domain_model_core');
        return $queryBuilder
            ->select('tx_t3monitoring_domain_model_core.version', 'tx_t3monitoring_domain_model_core.version_integer', 'tx_t3monitoring_domain_model_core.insecure',
                'tx_t3monitoring_domain_model_core.is_stable', 'tx_t3monitoring_domain_model_core.is_active', 'tx_t3monitoring_domain_model_core.is_latest')
            ->selectLiteral('count(tx_t3monitoring_domain_model_core.version) as count, tx_t3monitoring_domain_model_core.version, tx_t3monitoring_domain_model_core.version_integer, tx_t3monitoring_domain_model_core.insecure as insecureCore
            ,tx_t3monitoring_domain_model_core.is_stable,tx_t3monitoring_domain_model_core.is_active,tx_t3monitoring_domain_model_core.is_latest')
            ->from('tx_t3monitoring_domain_model_core')
            ->where(
                $queryBuilder->expr()->eq('hidden', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT))
            )
            ->leftJoin(
                'tx_t3monitoring_domain_model_core',
                'tx_t3monitoring_domain_model_client',
                'tx_t3monitoring_domain_model_client',
                $queryBuilder->expr()->eq('tx_t3monitoring_domain_model_client.core', $queryBuilder->quoteIdentifier('tx_t3monitoring_domain_model_core.uid'))
            )
            ->orderBy('tx_t3monitoring_domain_model_core.version_integer')
            ->groupBy('tx_t3monitoring_domain_model_core.version', 'version_integer', 'insecure', 'is_stable', 'is_latest', 'is_active')
            ->executeQuery()->fetchAllAssociative();
    }

    public function getUsedCoreVersionCountJson(): string
    {
        $data = $this->getUsedCoreVersionCount();
        $result = [];
        foreach ($data as $row) {
            $result[] = [$row['version'], (int)$row['count']];
        }
        return json_encode($result);
    }
}
