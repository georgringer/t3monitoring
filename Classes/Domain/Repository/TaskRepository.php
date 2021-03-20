<?php
namespace T3Monitor\T3monitoring\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class TaskRepository extends BaseRepository
{
    public function initializeObject(): void
    {
        $this->setDefaultOrderings(['client' => QueryInterface::ORDER_ASCENDING]);
    }

    /**
     * Function returns all tasks for a specific clientId
     *
     * @param integer $clientId The id of the desired client
     *
     * @return array<array> The tasks of this client
     */
    public function findByClientId(int $clientId)
    {
        $query = $this->getQuery();
        $query->matching($query->equals('client', intval($clientId)));
        $tasks = $query->execute()->toArray();
        return $tasks;
    }
}
