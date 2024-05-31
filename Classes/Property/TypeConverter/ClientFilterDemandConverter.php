<?php

namespace T3Monitor\T3monitoring\Property\TypeConverter;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface;
use TYPO3\CMS\Extbase\Property\TypeConverter\AbstractTypeConverter;

/**
 * Class ClientFilterDemandConverter
 */
class ClientFilterDemandConverter extends AbstractTypeConverter
{
    /**
     * Actually convert from $source to $targetType, by doing a typecast.
     *
     * @param mixed                                 $source
     * @param string                                $targetType
     * @param array                                 $convertedChildProperties
     * @param PropertyMappingConfigurationInterface $configuration
     *
     * @return float|\TYPO3\CMS\Extbase\Error\Error
     * @api
     */
    public function convertFrom(
        $source,
        $targetType,
        array $convertedChildProperties = [],
        PropertyMappingConfigurationInterface $configuration = null
    ) {
        if (!$this->isAllowed()) {
            return null;
        }

        $properties = $this->getFilterParams();

        $object = GeneralUtility::makeInstance($targetType);
        if (isset($properties)) {
            foreach ($properties as $key => $value) {
                if (property_exists($object, $key)) {
                    $setter = 'set' . ucfirst($key);
                    $object->$setter($value);
                }
            }
        }

        return $object;
    }

    public function canConvertFrom($source, string $targetType): bool
    {
        return $this->isAllowed();
    }

    /**
     * @return bool
     */
    protected function isAllowed()
    {
        $vars = $this->getFilterParams();

        return isset($vars) && is_array($vars);
    }

    protected function getFilterParams()
    {
        return $GLOBALS['TYPO3_REQUEST']->getQueryParams()['filter'] ?? $GLOBALS['TYPO3_REQUEST']->getParsedBody(
        )['filter'] ?? null;
    }
}
