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
use TYPO3\CMS\Extbase\Error\Error;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface;
use TYPO3\CMS\Extbase\Property\TypeConverter\AbstractTypeConverter;

class ClientFilterDemandConverter extends AbstractTypeConverter
{
    /**
     * @var list<string>
     */
    protected array $sourceTypes = ['array', 'string'];

    protected string $targetType = ClientFilterDemand::class;

    protected int $priority = 10;

    /**
     * Actually convert from $source to $targetType, by doing a typecast.
     *
     * @api
     */
    public function convertFrom(
        $source,
        string $targetType,
        array $convertedChildProperties = [],
        ?PropertyMappingConfigurationInterface $configuration = null
    ): ?ClientFilterDemand {
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
        return $request->getParsedBody()['filter'] ?? $request->getQueryParams()['filter'] ?? [];
    }
}
