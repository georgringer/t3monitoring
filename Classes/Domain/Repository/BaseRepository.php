<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\Domain\Repository;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class BaseRepository extends Repository
{
    public function findAll(): QueryResultInterface
    {
        $query = $this->getQuery();
        return $query->execute();
    }

    public function countAll(): int
    {
        $query = $this->getQuery();
        return $query->execute()->count();
    }

    protected function getQuery(): QueryInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        return $query;
    }
}
