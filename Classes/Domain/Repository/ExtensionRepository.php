<?php

namespace T3Monitor\T3monitoring\Domain\Repository;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Dto\ExtensionFilterDemand;
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
        $connection = $this->getDatabaseConnection();
        $qb = $connection->createQueryBuilder();
        $qb->select('client.title', 'client.uid as clientUid', 'ext.name', 'ext.version', 'ext.insecure')
            ->from('tx_t3monitoring_domain_model_extension', 'ext')
            ->rightJoin('ext', 'tx_t3monitoring_client_extension_mm', 'mm', 'mm.uid_foreign = ext.uid')
            ->rightJoin('mm', 'tx_t3monitoring_domain_model_client', 'client', 'mm.uid_local=client.uid');
        $eb = $qb->expr();
        $conditions = $eb->andX(
            $eb->isNotNull('ext.name'),
            $eb->eq('client.deleted', 0),
            $eb->eq('client.hidden', 0)
            //$this->extendWhereClause($demand)
        );
        $qb->orderBy('ext.name', 'ASC');
        $qb->orderBy('ext.version_integer', 'DESC');
        $qb->orderBy('client.title', 'ASC');
        $qb->andWhere($conditions);

        $rows = $qb->execute()->fetchAll();
        foreach ($rows as $row) {
            $result[$row['name']][$row['version']]['insecure'] = $row['insecure'];
            $result[$row['name']][$row['version']]['clients'][] = $row;
        }
        return $result;
    }

    /**
     * @param ExtensionFilterDemand $demand
     * @return string
     */
    protected function extendWhereClause(ExtensionFilterDemand $demand)
    {
        $table = 'tx_t3monitoring_domain_model_extension';
        $constraints = [];
        // name
        if ($demand->getName()) {
            $searchString = $this->getDatabaseConnection()->quoteStr($demand->getName(), $table);

            if ($demand->isExactSearch()) {
                $constraints[] = 'ext . name = "' . $searchString . '"';
            } else {
                $constraints[] = 'ext . name LIKE "%' .
                    $this->getDatabaseConnection()->escapeStrForLike($searchString, $table) . '%"';
            }
        }

        if (!empty($constraints)) {
            return ' AND ' . implode(' AND ', $constraints);
        }
        return '';
    }
}
