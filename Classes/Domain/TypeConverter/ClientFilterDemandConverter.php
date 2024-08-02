<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\Domain\TypeConverter;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Dto\ClientFilterDemand;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface;
use TYPO3\CMS\Extbase\Property\TypeConverter\AbstractTypeConverter;

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
     * @param PropertyMappingConfigurationInterface|null $configuration
     * @return float|\TYPO3\CMS\Extbase\Error\Error
     * @api
     */
    public function convertFrom(
        $source,
        $targetType,
        array $convertedChildProperties = [],
        PropertyMappingConfigurationInterface $configuration = null
    ) {
        $properties = $this->getProperties();
        if (!$properties) {
            return null;
        }

        $object = GeneralUtility::makeInstance($this->targetType);
        foreach ($properties as $key => $value) {
            if (property_exists($object, $key)) {
                $setter = 'set' . ucfirst($key);
                $object->$setter($value);
            }
        }
        return $object;
    }

    public function canConvertFrom($source, string $targetType): bool
    {
        return $this->getProperties() !== [];
    }

    protected function getProperties(): array
    {
        /** @var ServerRequest $request */
        $request = $GLOBALS['TYPO3_REQUEST'];
        $vars = $request->getQueryParams()['tx_t3monitoring_tools_t3monitoringt3monitor'] ?? [];
        return is_array($vars['filter'] ?? false) ? $vars['filter'] : [];
    }
}
