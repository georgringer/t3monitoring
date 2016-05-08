<?php
namespace T3Monitor\T3monitoring\Domain\Repository;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Dto\ClientFilterDemand;

/**
 * The repository for Clients
 */
class StatisticRepository extends BaseRepository
{

    public function getClientsByDemand(ClientFilterDemand $demand, $returnFirst = false)
    {
        $where = 'tx_t3monitoring_domain_model_client.deleted=0';

        // version
        $version = $demand->getVersion();
        if ($version) {
            $where .= ' AND tx_t3monitoring_domain_model_core.version_integer=' . (int)$version;
        }

        $rows = $this->getDatabaseConnection()->exec_SELECTgetRows(
            'tx_t3monitoring_domain_model_client.uid,tx_t3monitoring_domain_model_client.title, tx_t3monitoring_domain_model_client.domain,
                tx_t3monitoring_domain_model_core.version, tx_t3monitoring_domain_model_core.insecure as insecureCore
                ,tx_t3monitoring_domain_model_core.is_stable,tx_t3monitoring_domain_model_core.is_active,tx_t3monitoring_domain_model_core.is_latest
                ',
            'tx_t3monitoring_domain_model_client RIGHT JOIN tx_t3monitoring_domain_model_core
                ON tx_t3monitoring_domain_model_client.core = tx_t3monitoring_domain_model_core.uid',
            $where, '', 'tx_t3monitoring_domain_model_client.title');

        if ($returnFirst) {
            return array_shift($rows);
        }
        return $rows;
    }

    public function getCoreVersion($version)
    {
        return $this->getDatabaseConnection()->exec_SELECTgetSingleRow('*', 'tx_t3monitoring_domain_model_core',
            'version_integer=' . (int)$version);
    }

    public function getUsedCoreVersionCount()
    {
        return $this->getDatabaseConnection()->exec_SELECTgetRows(
            'count(tx_t3monitoring_domain_model_core.version) as count, tx_t3monitoring_domain_model_core.version, tx_t3monitoring_domain_model_core.version_integer, tx_t3monitoring_domain_model_core.insecure as insecureCore
            ,tx_t3monitoring_domain_model_core.is_stable,tx_t3monitoring_domain_model_core.is_active,tx_t3monitoring_domain_model_core.is_latest',
            'tx_t3monitoring_domain_model_client RIGHT JOIN tx_t3monitoring_domain_model_core
                ON tx_t3monitoring_domain_model_client.core = tx_t3monitoring_domain_model_core.uid',
            'tx_t3monitoring_domain_model_client.deleted=0', 'tx_t3monitoring_domain_model_core.version,version_integer,insecureCore,is_stable,is_latest,is_active',
            'tx_t3monitoring_domain_model_core.version_integer');
    }
}
