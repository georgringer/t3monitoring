<?php
namespace T3Monitor\T3monitoring\Domain\Repository;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class CheckResultRepository extends BaseRepository
{
    public function findAllWithFailCount()
    {
        $queryBuilder = $this->getDatabaseConnection()->createQueryBuilder();
        $result = $queryBuilder->addSelectLiteral('
                uid,
                title,
                description,
                message_category,
                (
                    SELECT 
                        COUNT(*)
                    FROM tx_t3monitoring_checkresult_failed_rules_mm 
                    LEFT JOIN tx_t3monitoring_domain_model_checkresult 
                        ON tx_t3monitoring_domain_model_checkresult.uid=tx_t3monitoring_checkresult_failed_rules_mm.uid_local 
                    WHERE uid_foreign=tx_t3monitoring_domain_model_rule.uid 
                        AND tx_t3monitoring_domain_model_checkresult.uid IN (SELECT check_result FROM tx_t3monitoring_domain_model_client)
                ) as fail_count')
            ->from('tx_t3monitoring_domain_model_rule')
            ->orderBy('title')
            ->execute()
            ->fetchAll();

        return $result;
    }
}
