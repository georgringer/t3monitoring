<?php

namespace T3Monitor\T3monitoring\Domain\TypeConverter;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Dto\ClientFilterDemand;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface;
use TYPO3\CMS\Extbase\Property\TypeConverter\AbstractTypeConverter;

/**
 * Class ClientFilterDemandConverter
 */
class ClientFilterDemandConverter extends AbstractTypeConverter
{
    /**
     * @var array<string>
     */
    protected $sourceTypes = ['array', 'string'];

    /**
     * @var string
     */
    protected $targetType = ClientFilterDemand::class;

    /**
     * @var int
     */
    protected $priority = 10;

    /**
     * Actually convert from $source to $targetType, by doing a typecast.
     *
     * @param mixed $source
     * @param string $targetType
     * @param array $convertedChildProperties
     * @param PropertyMappingConfigurationInterface $configuration
     * @return float|\TYPO3\CMS\Extbase\Error\Error
     * @api
     */
    public function convertFrom(
        $source,
        $targetType,
        array $convertedChildProperties = array(),
        PropertyMappingConfigurationInterface $configuration = null
    ) {
        if (!$this->isAllowed()) {
            return null;
        }
        $vars = GeneralUtility::_GET('tx_t3monitoring_tools_t3monitoringt3monitor');
        $properties = $vars['filter'];

        $object = GeneralUtility::makeInstance($this->targetType);
        foreach ($properties as $key => $value) {
            if (property_exists($object, $key)) {
                $setter = 'set' . ucfirst($key);
                $object->$setter($value);
            }
        }
        return $object;
    }

    /**
     * @param mixed $source
     * @param string $targetType
     * @return bool
     */
    public function canConvertFrom($source, $targetType)
    {
        return $this->isAllowed();
    }

    /**
     * @return bool
     */
    protected function isAllowed()
    {
        $vars = GeneralUtility::_GET('tx_t3monitoring_tools_t3monitoringt3monitor');
        if (!isset($vars) || !is_array($vars['filter'])) {
            return false;
        }

        return true;
    }
}
