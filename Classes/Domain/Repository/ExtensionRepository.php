<?php

namespace T3Monitor\T3monitoring\Domain\Repository;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Dto\ExtensionFilterDemand;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * The repository for Extensions
 */
class ExtensionRepository extends BaseRepository
{

    /**
     * Initialize object
     */
    public function initializeObject()
    {
        $this->setDefaultOrderings(['name' => QueryInterface::ORDER_ASCENDING]);
    }

    /**
     * @param string $name
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAllVersionsByName($name)
    {
        $query = $this->getQuery();
        $query->setOrderings(['versionInteger' => QueryInterface::ORDER_DESCENDING]);
        $query->matching(
            $query->logicalAnd($query->equals('name', $name))
        );

        return $query->execute();
    }

    /**
     * @param ExtensionFilterDemand $demand
     * @return array
     */
    public function findByDemand(ExtensionFilterDemand $demand)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_t3monitoring_domain_model_extension');
        $expressionBuilder = $queryBuilder->expr();
        $queryBuilder
            ->select('client.title', 'client.uid as clientUid', 'ext.name', 'ext.version', 'ext.insecure')
            ->from('tx_t3monitoring_domain_model_extension', 'ext')
            ->rightJoin('ext', 'tx_t3monitoring_client_extension_mm', 'mm', 'mm.uid_foreign = ext.uid')
            ->rightJoin('mm', 'tx_t3monitoring_domain_model_client', 'client', 'mm.uid_local=client.uid')
            ->where($expressionBuilder->isNotNull('ext.name'))
            ->andWhere($expressionBuilder->eq('client.hidden', 0))
            ->andWhere($expressionBuilder->eq('client.deleted',0))
            ->orderBy('ext.name', 'ASC')
            ->orderBy('ext.version_integer', 'DESC')
            ->orderBy('client.title', 'ASC');
        $this->extendWhereClause($demand, $queryBuilder, $expressionBuilder);

        $result = [];
        foreach ($queryBuilder->execute()->fetchAll() as $row) {
            $result[$row['name']][$row['version']]['insecure'] = $row['insecure'];
            $result[$row['name']][$row['version']]['clients'][] = $row;
        }

        return $result;
    }

    /**
     * @param ExtensionFilterDemand $demand
     * @param QueryBuilder          $queryBuilder
     * @param ExpressionBuilder     $expressionBuilder
     */
    protected function extendWhereClause(ExtensionFilterDemand $demand, QueryBuilder &$queryBuilder, ExpressionBuilder $expressionBuilder)
    {
        if ($demand->getName()) {
            if ($demand->isExactSearch()) {
                $queryBuilder->andWhere($expressionBuilder->eq('ext.name', $queryBuilder->createNamedParameter($demand->getName())));
            } else {
                $queryBuilder->andWhere($expressionBuilder->like('ext.name', $queryBuilder->createNamedParameter('%' . $demand->getName() . '%')));
            }
        }
    }
}
