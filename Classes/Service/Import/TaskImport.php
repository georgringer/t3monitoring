<?php
namespace T3Monitor\T3monitoring\Service\Import;

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
    public function importTasks(array $client, array $tasks): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(self::TABLE);
        $statement = $queryBuilder
            ->select('uid', 'client_task_uid')
            ->from(self::TABLE)
            ->where(
                $queryBuilder->expr()->eq('deleted', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                $queryBuilder->expr()->eq('client', $queryBuilder->createNamedParameter(intval($client['uid'], \PDO::PARAM_INT)))
            )
            ->execute();

        if (is_object($statement)) {
            $rows = $statement->fetchAll();

            $previousTasks = [];
            foreach ($rows as $row) {
                $previousTasks[$row['client_task_uid']] = $row;
            }

            $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable(self::TABLE);
            $connection->beginTransaction();
            try {
                foreach ($tasks as $task) {
                    $task['lastexecution'] = $task['lastexecution_time'];
                    unset($task['lastexecution_time']);

                    $task['pid'] = $this->emConfiguration->getPid();
                    $task['client_task_uid'] = $task['uid'];
                    $task['tstamp'] = $GLOBALS['EXEC_TIME'];
                    $task['client'] = $client['uid'];

                    if (isset($previousTasks[$task['uid']])) {
                        $connection->update(
                            self::TABLE,
                            $task,
                            [
                                'uid' => $previousTasks[$task['uid']]['uid'],
                                'client' => $client['uid']
                            ],
                        );
                    } else {
                        unset($task['uid']);
                        $task['crdate'] = $GLOBALS['EXEC_TIME'];
                        $connection->insert(
                            self::TABLE,
                            $task
                        );
                    }
                }
                $connection->commit();
            } catch (\Exception $e) {
                $connection->rollBack();
                throw $e;
            }
        }
    }
}
