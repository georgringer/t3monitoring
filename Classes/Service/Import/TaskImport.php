<?php
namespace T3Monitor\T3monitoring\Service\Import;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class TaskImport extends BaseImport
{
    const TABLE = 'tx_t3monitoring_domain_model_task';

    /**
     * Import the given tasks for a client into the database.
     *
     * @param array<string> $client The client
     * @param array<string> $tasks The client's scheduled tasks
     *
     * @return void
     */
    public function importTasks(array $client, array $importTasks): void
    {
        $currentTasks = $this->getTasksForClient($client['uid']);

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable(self::TABLE);
        $connection->beginTransaction();
        try {
            foreach ($importTasks as $importTask) {
                $importTask['lastexecution'] = $importTask['lastexecution_time'];
                unset($importTask['lastexecution_time']);

                $importTask['pid'] = $this->emConfiguration->getPid();

                $importTask['client_task_uid'] = $importTask['uid'];
                $importTask['tstamp'] = $GLOBALS['EXEC_TIME'];
                $importTask['client'] = $client['uid'];

                if (isset($currentTasks[$importTask['uid']])) {
                    $importTaskUid = $importTask['uid'];
                    unset($importTask['uid']);
                    $connection->update(
                        self::TABLE,
                        $importTask,
                        [
                            'uid' => $currentTasks[$importTaskUid],
                            'client' => $client['uid']
                        ],
                    );
                    unset($currentTasks[$importTaskUid]);
                } else {
                    unset($importTask['uid']);
                    $importTask['crdate'] = $GLOBALS['EXEC_TIME'];
                    $connection->insert(
                        self::TABLE,
                        $importTask
                    );
                }
            }
            foreach ($currentTasks as $deleteTaskUid) {
                $connection->delete(
                    self::TABLE,
                    ['uid' => (int)$deleteTaskUid],
                    [Connection::PARAM_INT]
                );
            }
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            throw $e;
        }
    }

    /**
     * Returns the the uid and the client_task_uid of all tasks of
     * a specific client.
     *
     * @param int $clientUid The uid of the client
     *
     * @return array<string> Array holding the UIDs of the tasks in this database
     */
    private function getTasksForClient(int $clientUid): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(self::TABLE);
        $statement = $queryBuilder
            ->select('uid', 'client_task_uid')
            ->from(self::TABLE)
            ->where(
                $queryBuilder->expr()->eq('deleted', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                $queryBuilder->expr()->eq('client', $queryBuilder->createNamedParameter(intval($clientUid, \PDO::PARAM_INT)))
            )
            ->execute();

        if (is_object($statement)) {
            $rows = $statement->fetchAll();
            $currentTasks = [];
            foreach ($rows as $row) {
                $currentTasks[$row['client_task_uid']] = $row['uid'];
            }
            return $currentTasks;
        }
        return [];
    }
}
