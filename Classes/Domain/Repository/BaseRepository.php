<?php
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
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Base repository
 */
class BaseRepository extends Repository
{
    /**
     * @return Connection
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function getDatabaseConnection() : Connection
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionByName(ConnectionPool::DEFAULT_CONNECTION_NAME);
    }

    /**
     * @return QueryResultInterface
     */
    public function findAll()
    {
        $query = $this->getQuery();
        return $query->execute();
    }

    /**
     * @return int
     */
    public function countAll()
    {
        $query = $this->getQuery();
        return $query->execute()->count();
    }

    /**
     * @return QueryInterface
     */
    protected function getQuery()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        return $query;
    }
}
