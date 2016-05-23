<?php
namespace T3Monitor\T3monitoring\Domain\Repository;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Client;
use T3Monitor\T3monitoring\Domain\Model\Dto\ClientFilterDemand;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * The repository for Clients
 */
class ClientRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $searchFields = ['title', 'domain'];

    /**
     * @param ClientFilterDemand $demand
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByDemand(ClientFilterDemand $demand)
    {
        $query = $this->getQuery();
        $constraints = $this->getConstraints($demand, $query);

        if (!empty($constraints)) {
            $query->matching(
                $query->logicalAnd($constraints)
            );
        }

        return $query->execute();
    }

    /**
     * @param ClientFilterDemand $demand
     * @return int
     */
    public function countByDemand(ClientFilterDemand $demand)
    {
        $query = $this->getQuery();
        $constraints = $this->getConstraints($demand, $query);
        if (!empty($constraints)) {
            $query->matching(
                $query->logicalAnd($constraints)
            );
        }
        return $query->execute()->count();
    }

    /**
     * @param bool $emailAddressRequired
     * @return \T3Monitor\T3monitoring\Domain\Model\Client[]
     */
    public function getAllForReport($emailAddressRequired = false)
    {
        $query = $this->getQuery();
        $demand = $this->getFilterDemand();
        $demand->setWithInsecureCore(true);
        $demand->setWithInsecureExtensions(true);

        $constraints[] = $query->logicalOr(
            $this->getConstraints($demand, $query, true
            )
        );

        if ($emailAddressRequired) {
            $constraints[] = $query->logicalNot(
              $query->equals('email', '')
            );
        }

        $query->matching(
            $query->logicalAnd($constraints)
        );

        return $query->execute();
    }

    /**
     * @param ClientFilterDemand $demand
     * @param QueryInterface $query
     */
    protected function getConstraints(ClientFilterDemand $demand, QueryInterface $query)
    {
        $constraints = [];

        // SLA
        if ($demand->getSla()) {
            $constraints[] = $query->equals('sla', $demand->getSla());
        }

        // Search
        if ($demand->getSearchWord()) {
            $searchConstraints = [];
            foreach ($this->searchFields as $field) {
                $searchConstraints[] = $query->like($field, '%' . $demand->getSearchWord() . '%');
            }
            if (count($searchConstraints)) {
                $constraints[] = $query->logicalOr($searchConstraints);
            }
        }

        // Version
        if ($demand->getVersion()) {
            $split = explode('.', $demand->getVersion());
            if (count($split) === 3) {
                $constraints[] = $query->equals('core.version', $demand->getVersion());
            } else {
                $constraints[] = $query->like('core.version', $demand->getVersion() . '%');
            }
        }

        // error message
        if ($demand->isWithErrorMessage()) {
            $constraints[] = $query->logicalNot($query->equals('errorMessage', ''));
        }

        // insecure extensions
        if ($demand->isWithInsecureExtensions()) {
            $constraints[] = $query->equals('extensions.insecure', 1);
        }

        // outdated extensions
        if ($demand->isWithOutdatedExtensions()) {
            $constraints[] = $query->equals('extensions.isLatest', 0);
        }

        // insecure core
        if ($demand->isWithInsecureCore()) {
            $constraints[] = $query->equals('core.insecure', 1);
        }

        // outdated core
        if ($demand->isWithOutdatedCore()) {
            $constraints[] = $query->logicalOr([
                $query->equals('core.isLatest', 0),
                $query->equals('core.isActive', 0)
            ]);
        }

        // extra info
        if ($demand->isWithExtraInfo()) {
            $constraints[] = $query->logicalNot($query->equals('extraInfo', ''));
        }

        // extra warning
        if ($demand->isWithExtraWarning()) {
            $constraints[] = $query->logicalNot($query->equals('extraWarning', ''));
        }

        // extra danger
        if ($demand->isWithExtraDanger()) {
            $constraints[] = $query->logicalNot($query->equals('extraDanger', ''));
        }

        return $constraints;
    }

    /**
     * @return ClientFilterDemand
     */
    protected function getFilterDemand()
    {
        return GeneralUtility::makeInstance(ClientFilterDemand::class);
    }
}
