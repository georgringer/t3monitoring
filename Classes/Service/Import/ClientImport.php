<?php

namespace T3Monitor\T3monitoring\Service\Import;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Exception;
use T3Monitor\T3monitoring\Domain\Model\Extension;
use T3Monitor\T3monitoring\Notification\EmailNotification;
use T3Monitor\T3monitoring\Service\DataIntegrity;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

/**
 * Class ClientImport
 */
class ClientImport extends BaseImport
{
    const TABLE = 'tx_t3monitoring_domain_model_client';

    /** @var array */
    protected $coreVersions = [];

    /** @var array */
    protected $responseCount = ['error' => 0, 'success' => 0];

    /** @var array */
    protected $failedClients = [];

    /** @var  EmailNotification */
    protected $emailNotification;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->coreVersions = $this->getAllCoreVersions();
        $this->emailNotification = GeneralUtility::makeInstance(EmailNotification::class);
        parent::__construct();
    }

    /**
     * @param int $clientId
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function run(int $clientId = 0)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable(self::TABLE);
        $query = $queryBuilder
            ->select('*')
            ->from(self::TABLE);
        if ($clientId > 0) {
            $query->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($clientId, \PDO::PARAM_INT))
            );
        }

        $clientRows = $query->execute()->fetchAll();

        foreach ($clientRows as $client) {
            $this->importSingleClient($client);
        }

        if ($this->responseCount['error'] > 0) {
            $this->emailNotification->sendClientFailedEmail($this->failedClients);
        }

        $dataIntegrity = GeneralUtility::makeInstance(DataIntegrity::class);
        $dataIntegrity->invokeAfterClientImport();
        $this->setImportTime('client');
    }

    /**
     * @return array
     */
    public function getResponseCount()
    {
        return $this->responseCount;
    }

    /**
     * @param array $row
     * @throws \RuntimeException
     */
    protected function importSingleClient(array $row)
    {
        try {
            $response = $this->requestClientData($row);
            if (empty($response)) {
                throw new \RuntimeException('Empty response from client ' . $row['title']);
            }
            $json = json_decode($response, true);
            if (!is_array($json)) {
                throw new \RuntimeException('Invalid response from client ' . $row['title']);
            }

            $update = [
                'tstamp' => $GLOBALS['EXEC_TIME'],
                'last_successful_import' => $GLOBALS['EXEC_TIME'],
                'error_message' => '',
                'php_version' => $json['core']['phpVersion'],
                'mysql_version' => $json['core']['mysqlClientVersion'],
                'disk_total_space' => $json['core']['diskTotalSpace'],
                'disk_free_space' => $json['core']['diskFreeSpace'],
                'core' => $this->getUsedCore($json['core']['typo3Version']),
                'extensions' => $this->handleExtensionRelations($row['uid'], (array)$json['extensions']),
            ];

            $this->addExtraData($json, $update, 'info');
            $this->addExtraData($json, $update, 'warning');
            $this->addExtraData($json, $update, 'danger');

            $connection = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable(self::TABLE);
            $connection->update(self::TABLE, $update, ['uid' => (int)$row['uid']]);

            $this->responseCount['success']++;
        } catch (Exception $e) {
            $this->handleError($row, $e);
        }
    }

    /**
     * Add extra information for info, warning, danger
     *
     * @param array $json
     * @param array $update
     * @param string $field
     */
    protected function addExtraData(array $json, array &$update, $field)
    {
        $dbField = 'extra_' . $field;
        if (isset($json['extra']) && is_array($json['extra'][$field])) {
            $update[$dbField] = json_encode($json['extra'][$field]);
        } else {
            $update[$dbField] = '';
        }
    }

    /**
     * @param array $client
     * @param Exception $error
     */
    protected function handleError(array $client, Exception $error)
    {
        $this->responseCount['error']++;
        $this->failedClients[] = $client;

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable(self::TABLE);
        $connection->update(self::TABLE,
            [
                'error_message' => $error->getMessage()
            ],
            [
                'uid' => (int)$client['uid']
            ]
        );
    }

    /**
     * @param array $row
     *
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    protected function requestClientData(array $row)
    {
        $domain = $this->unifyDomain($row['domain']);
        $url = $domain . '/index.php?eID=t3monitoring&secret=' . rawurlencode($row['secret']);
        $report = [];
        $headers = [
            'User-Agent: TYPO3-Monitoring/' . ExtensionManagementUtility::getExtensionVersion('t3monitoring'),
            'Accept: application/json'
        ];
        if (!empty($row['basic_auth_username']) && !empty($row['basic_auth_password'])) {
            $headers[] = 'Authorization: Basic ' . base64_encode($row['basic_auth_username'] . ':' . $row['basic_auth_password']);
        }
        $response = GeneralUtility::getUrl($url, 0, $headers, $report);
        if (!empty($report['message']) && $report['message'] !== 'OK') {
            throw new \RuntimeException($report['message']);
        }
        return $response;
    }

    /**
     * @param string $domain
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function unifyDomain($domain)
    {
        $domain = rtrim($domain, '/');
        if (!StringUtility::beginsWith($domain, 'http://') && !StringUtility::beginsWith($domain, 'https://')) {
            $domain = 'http://' . $domain;
        }

        return $domain;
    }

    /**
     * @param int $client client uid
     * @param array $extensions list of extensions
     * @return int count of used extensions
     */
    protected function handleExtensionRelations($client, array $extensions = [])
    {
        $table = 'tx_t3monitoring_domain_model_extension';
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table);

        $whereClause = [];
        foreach ($extensions as $key => $data) {
            $whereClause[] = $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('version', $queryBuilder->createNamedParameter($data['version'])),
                    $queryBuilder->expr()->eq('name', $queryBuilder->createNamedParameter($key))
                );
        }

        $existingExtensions = $queryBuilder
            ->select('uid', 'version', 'name')
            ->from($table)
            ->where($queryBuilder->expr()->orX(...$whereClause))
            ->execute()
            ->fetchAll();

        $relationsToBeAdded = [];
        foreach ($extensions as $key => $data) {
            // search if exists
            $found = null;
            foreach ($existingExtensions as $existingExtension) {
                if ($existingExtension['name'] === $key && $existingExtension['version'] === $data['version']) {
                    $found = $existingExtension;
                    continue;
                }
            }

            if ($found) {
                $relationId = $found['uid'];
            } else {
                $insert = [
                    'pid' => $this->emConfiguration->getPid(),
                    'name' => $key,
                    'version' => (string)$data['version'],
                    'version_integer' => VersionNumberUtility::convertVersionNumberToInteger($data['version']),
                    'title' => (string)$data['title'],
                    'description' => (string)$data['description'],
                    'state' => array_search($data['state'], Extension::$defaultStates, true),
                    'is_official' => 0,
                    'tstamp' => $GLOBALS['EXEC_TIME'],
                ];

                $connection = $this->getConnectionTableFor($table);
                $connection->insert('tx_t3monitoring_domain_model_extension', $insert);
                $relationId = $connection->lastInsertId('tx_t3monitoring_domain_model_extension');
            }
            $fields = ['uid_local', 'uid_foreign', 'title', 'state', 'is_loaded'];
            $relationsToBeAdded[] = [
                $client,
                $relationId,
                $data['title'],
                array_search($data['state'], Extension::$defaultStates, true),
                $data['isLoaded'],
            ];

            $mmTable = 'tx_t3monitoring_client_extension_mm';
            $mmConnection = $this->getConnectionTableFor($mmTable);
            $mmConnection->delete($mmTable, ['uid_local' => (int)$client]);
            $mmConnection = $this->getConnectionTableFor($mmTable);
            $mmConnection->bulkInsert($mmTable, $relationsToBeAdded, $fields);
        }

        return count($extensions);
    }

    /**
     * @param string $version
     * @return int
     */
    protected function getUsedCore(string $version): int
    {
        if (isset($this->coreVersions[$version])) {
            return $this->coreVersions[$version]['uid'];
        }

        // insert new core
        $connection = $this->getConnectionTableFor('tx_t3monitoring_domain_model_core');

        $insert = [
            'pid' => $this->emConfiguration->getPid(),
            'is_official' => 0,
            'version' => $version,
            'version_integer' => VersionNumberUtility::convertVersionNumberToInteger($version),
            'insecure' => 1 // @todo to be discussed
        ];

        $connection->insert('tx_t3monitoring_domain_model_core', $insert);
        $newId = $connection->lastInsertId('tx_t3monitoring_domain_model_core');
        $this->coreVersions[$version] = ['uid' => $newId, 'version' => $version];

        return $newId;
    }

    /**
     * @return array
     */
    protected function getAllCoreVersions(): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_t3monitoring_domain_model_core');
        $rows = $queryBuilder
            ->select('uid', 'version')
            ->from('tx_t3monitoring_domain_model_core')
            ->execute()
            ->fetchAll();
        $finalRows = [];
        foreach ($rows as $row) {
            $finalRows[$row['version']] = $row;
        }
        return $finalRows;
    }

    private function getConnectionTableFor(string $table) : Connection
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable($table);
    }
}
