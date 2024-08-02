<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\Domain\Repository;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Core;
use T3Monitor\T3monitoring\Domain\Model\Dto\CoreFilterDemand;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class CoreRepository extends BaseRepository
{
    const USED_ALL = 0;
    const USED_ONLY = 1;

    public function initializeObject(): void
    {
        $this->setDefaultOrderings(['versionInteger' => QueryInterface::ORDER_DESCENDING]);
    }

    public function findByDemand(CoreFilterDemand $demand): QueryResultInterface|array
    {
        $query = $this->getQuery();
        $query->matching(
            $query->equals('isUsed', $demand->getUsage())
        );

        return $query->execute();
    }

    public function findAllCoreVersions(int $mode = self::USED_ONLY): QueryResultInterface|array
    {
        $query = $this->getQuery();
        if ($mode > 0) {
            $query->matching(
                $query->equals('isUsed', ($mode === self::USED_ONLY ? 1 : 0))
            );
        }
        return $query->execute();
    }

    public function findByVersionAsInteger(string $version): Core
    {
        $query = $this->getQuery();
        return $query->matching(
            $query->equals('versionInteger', $version)
        )->execute()->getFirst();
    }
}
