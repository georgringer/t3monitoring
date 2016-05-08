<?php

namespace T3Monitor\T3monitoring\Domain\Model\Dto;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class ExtensionFilterDemand extends AbstractEntity
{

    /** @var string */
    protected $name;

    /** @var bool */
    protected $exactSearch;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isExactSearch()
    {
        return $this->exactSearch;
    }

    /**
     * @param bool $exactSearch
     */
    public function setExactSearch($exactSearch)
    {
        $this->exactSearch = $exactSearch;
    }
}
