<?php
namespace T3Monitor\T3monitoring\Domain\Repository;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * The repository for Tags
 */
class TagRepository extends BaseRepository
{

    /**
     * Initialize object
     */
    public function initializeObject()
    {
        $this->setDefaultOrderings(['sorting' => QueryInterface::ORDER_ASCENDING]);
    }
}
