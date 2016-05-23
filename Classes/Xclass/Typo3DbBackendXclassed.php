<?php
namespace T3Monitor\T3monitoring\Xclass;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbBackend;

/**
 * Xclassed Typo3DbBackend
 */
class Typo3DbBackendXclassed extends Typo3DbBackend
{

    /**
     * Fetches the rows directly from the database, not using prepared statement
     *
     * @param array $statementParts
     * @return array the result
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Storage\Exception\SqlErrorException
     */
    protected function getRowsFromDatabase(array $statementParts)
    {
        $queryCommandParameters = $this->createQueryCommandParametersFromStatementParts($statementParts);

        // Xclass start
        if ($queryCommandParameters['orderBy'] === 'tx_t3monitoring_client_extension_mm.sorting ASC' && $queryCommandParameters['selectFields'] === ' tx_t3monitoring_domain_model_extension.*') {
            $queryCommandParameters['orderBy'] = 'tx_t3monitoring_domain_model_extension.insecure DESC, tx_t3monitoring_client_extension_mm.is_loaded ASC, tx_t3monitoring_domain_model_extension.name asc';
        }
        // Xclass end

        $rows = $this->databaseHandle->exec_SELECTgetRows(
            $queryCommandParameters['selectFields'],
            $queryCommandParameters['fromTable'],
            $queryCommandParameters['whereClause'],
            '',
            $queryCommandParameters['orderBy'],
            $queryCommandParameters['limit']
        );
        $this->checkSqlErrors();

        return $rows;
    }
}
