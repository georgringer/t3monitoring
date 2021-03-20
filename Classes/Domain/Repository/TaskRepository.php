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

    /**
     * Method returns the overall task status for a given client.
     * Depending on the Function checks the overall task status.
     * Depending on the tasks following return values are valid:
     * - 0: All tasks are ok
     * - 1: At least 1 task is late, no task is failing
     * - 2: At least 1 task is failing
     *
     * @param integer $clientId
     * @return void
     */
    public function getTaskStatusForClientId(int $clientId)
    {
        $lateCount = 0;
        $errorCount = 0;

        $query = $this->getQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('client', intval($clientId)),
                $query->logicalOr([
                    $query->equals('late', 1),
                    $query->logicalNot($query->equals('lastexecution_failure', ''))
                ])
            )
        );
        $errorTasks = $query->execute()->toArray();

        if ($errorTasks) {
            foreach ($errorTasks as $errorTask) {
                if ($errorTask->getLate() == 1) {
                    $lateCount++;
                }
                if ($errorTask->getLastexecutionFailure() != '') {
                    $errorCount++;
                }
            }

            if ($errorCount) {
                return 2; // Overall: Error
            }
    
            if ($lateCount) {
                return 1; // Overall: Warning
            }
        } else {
            $query = $this->getQuery();
            $taskCount = $query->matching($query->equals('client', intval($clientId)))->count();
            if ($taskCount) {
                return 3; // Overall: Ok
            }
        }
        return 0; // OVerall: Empty
    }
}
