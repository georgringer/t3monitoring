<?php
namespace T3Monitor\T3monitoring\Service;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Database\DatabaseConnection;

/**
 * Class DataIntegrity
 */
class DataIntegrity
{

    /**
     * Invoke after core import
     */
    public function invokeAfterCoreImport()
    {
        $this->usedCore();
    }

    /**
     * Invoke after client import
     */
    public function invokeAfterClientImport()
    {
        $this->usedCore();
        $this->usedExtensions();
    }

    /**
     * Invoke after extension import
     */
    public function invokeAfterExtensionImport()
    {
        $this->getLatestExtensionVersion();
        $this->getNextSecureExtensionVersion();
        $this->usedExtensions();
    }

    /**
     * Get latest extension version
     */
    protected function getLatestExtensionVersion()
    {
        $table = 'tx_t3monitoring_domain_model_extension';

        // Patch release
        $queryResult = $this->getDatabaseConnection()->sql_query('
            SELECT name,major_version as major,minor_version as minor
            FROM tx_t3monitoring_domain_model_extension
            WHERE insecure = 0 AND version_integer > 0 AND is_official = 1
            GROUP BY name,major,minor'
        );

        while ($row = $this->getDatabaseConnection()->sql_fetch_assoc($queryResult)) {
            $where = 'name=' . $this->getDatabaseConnection()->fullQuoteStr($row['name'],
                    $table) . ' AND major_version=' . $row['major'] . ' AND minor_version=' . $row['minor'];
            $highestBugFixRelease = $this->getDatabaseConnection()->exec_SELECTgetSingleRow(
                'version',
                $table,
                $where,
                '',
                'version_integer desc'
            );
            if (is_array($highestBugFixRelease)) {
                $this->getDatabaseConnection()->exec_UPDATEquery($table, $where, [
                    'last_bugfix_release' => $highestBugFixRelease['version']
                ]);
            }
        }
        $this->getDatabaseConnection()->sql_free_result($queryResult);

        // Minor release
        $queryResult = $this->getDatabaseConnection()->sql_query('
            SELECT name,major_version as major
            FROM tx_t3monitoring_domain_model_extension
            WHERE insecure = 0 AND version_integer > 0 AND is_official = 1
            GROUP BY name,major'
        );

        while ($row = $this->getDatabaseConnection()->sql_fetch_assoc($queryResult)) {
            $where = 'name=' . $this->getDatabaseConnection()->fullQuoteStr($row['name'],
                    $table) . ' AND major_version=' . $row['major'];
            $highestBugFixRelease = $this->getDatabaseConnection()->exec_SELECTgetSingleRow(
                'version',
                $table,
                $where,
                '',
                'version_integer desc'
            );
            if (is_array($highestBugFixRelease)) {
                $this->getDatabaseConnection()->exec_UPDATEquery($table, $where, [
                    'last_minor_release' => $highestBugFixRelease['version']
                ]);
            }
        }
        $this->getDatabaseConnection()->sql_free_result($queryResult);

        // Major release
        $queryResult = $this->getDatabaseConnection()->sql_query(
            'SELECT a.version,a.name ' .
            'FROM ' . $table . ' a ' .
            'LEFT JOIN ' . $table . ' b ON a.name = b.name AND a.version_integer < b.version_integer ' .
            'WHERE b.name IS NULL ' .
            'ORDER BY a.uid'
        );

        while ($row = $this->getDatabaseConnection()->sql_fetch_assoc($queryResult)) {
            $where = 'name=' . $this->getDatabaseConnection()->fullQuoteStr($row['name'], $table);
            $this->getDatabaseConnection()->exec_UPDATEquery($table, $where, [
                'last_major_release' => $row['version']
            ]);
        }
        $this->getDatabaseConnection()->sql_free_result($queryResult);

        // mark latest version
        $this->getDatabaseConnection()->sql_query('
            UPDATE ' . $table . '
            SET is_latest=1 WHERE version=last_major_release
        ');
    }

    /**
     * Get next secure extension version
     */
    protected function getNextSecureExtensionVersion()
    {
        $table = 'tx_t3monitoring_domain_model_extension';
        $insecureExtensions = $this->getDatabaseConnection()->exec_SELECTgetRows(
            'uid,name,version_integer',
            $table,
            'insecure=1');
        foreach ($insecureExtensions as $row) {
            $where = sprintf(
                'insecure=0 AND name=%s AND version_integer>%s',
                $this->getDatabaseConnection()->fullQuoteStr($row['name'], $table),
                $row['version_integer']
            );
            $nextSecureVersion = $this->getDatabaseConnection()->exec_SELECTgetSingleRow(
                'uid,version',
                $table, $where);

            if (is_array($nextSecureVersion)) {
                $this->getDatabaseConnection()->exec_UPDATEquery(
                    $table,
                    'uid=' . $row['uid'],
                    ['next_secure_version' => $nextSecureVersion['version']]
                );
            }
        }
    }

    /**
     * Used core
     */
    protected function usedCore()
    {
        $table = 'tx_t3monitoring_domain_model_core';
        $coreRows = $this->getDatabaseConnection()->exec_SELECTgetRows(
            'tx_t3monitoring_domain_model_core.uid',
            'tx_t3monitoring_domain_model_client LEFT JOIN tx_t3monitoring_domain_model_core on tx_t3monitoring_domain_model_core.uid=tx_t3monitoring_domain_model_client.core',
            'tx_t3monitoring_domain_model_core.uid IS NOT NULL',
            '',
            '',
            '',
            'uid'
        );

        $this->getDatabaseConnection()->exec_UPDATEquery($table, '1=1', array('is_used' => 0));
        if (!empty($coreRows)) {
            $this->getDatabaseConnection()->exec_UPDATEquery(
                $table,
                sprintf('uid IN(%s)', implode(',', array_keys($coreRows))),
                array('is_used' => 1)
            );
        }
    }

    /**
     * Used extensions
     */
    protected function usedExtensions()
    {
        $clients = $this->getDatabaseConnection()->exec_SELECTgetRows(
            'uid,core',
            'tx_t3monitoring_domain_model_client',
            '1=1'
        );

        foreach ($clients as $client) {
            // count insecure extensions
            $countInsecure = $this->getDatabaseConnection()->exec_SELECTcountRows(
                'uid',
                'tx_t3monitoring_client_extension_mm
                    LEFT JOIN tx_t3monitoring_domain_model_extension
                        on tx_t3monitoring_client_extension_mm.uid_foreign=tx_t3monitoring_domain_model_extension.uid',
                'is_official=1 AND insecure = 1 AND tx_t3monitoring_client_extension_mm.uid_local=' . $client['uid']
            );
            // count outdated extensions
            $countOutdated = $this->getDatabaseConnection()->exec_SELECTcountRows(
                'uid',
                'tx_t3monitoring_client_extension_mm
                    LEFT JOIN tx_t3monitoring_domain_model_extension
                        on tx_t3monitoring_client_extension_mm.uid_foreign=tx_t3monitoring_domain_model_extension.uid',
                'is_official=1 AND insecure = 0 AND is_latest=0 AND tx_t3monitoring_client_extension_mm.uid_local=' . $client['uid']
            );
            // update client
            $this->getDatabaseConnection()->exec_UPDATEquery(
                'tx_t3monitoring_domain_model_client',
                'uid=' . $client['uid'],
                [
                    'insecure_extensions' => $countInsecure,
                    'outdated_extensions' => $countOutdated
                ]
            );
        }

        // Used extensions
        $this->getDatabaseConnection()->sql_query('
            UPDATE tx_t3monitoring_domain_model_extension
            SET is_used=1
            WHERE uid IN (
              SELECT uid_foreign FROM tx_t3monitoring_client_extension_mm
            );'
        );
    }

    /**
     * @return DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
