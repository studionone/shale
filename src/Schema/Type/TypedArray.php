<?php

declare(strict_types=1);

namespace Shale\Schema\Type;

use Shale\Exception\Schema\DataWasWrongTypeException;
use Shale\Exception\Schema\DataEncode\ValueWasWrongTypeException;
use Shale\Interfaces\Schema\SchemaNamedTypeInterface;
use Shale\Interfaces\Schema\SchemaTypeInterface;
use Shale\Interfaces\Schema\SchemaTypeWithTypedItemsInterface;
use Shale\Schema\TypeRegistry;

/**
 * Class TypedArray
 * @package Shale\Schema\Type
 *
 * Manually implemented getters/setters, to enforce typing
 */
class TypedArray implements SchemaTypeWithTypedItemsInterface
{
    /** @var SchemaNamedTypeInterface  */
    protected SchemaNamedTypeInterface $itemType;

    /**
     * TypedArray constructor.
     *
     * @param SchemaNamedTypeInterface $itemType
     */
    public function __construct(SchemaNamedTypeInterface $itemType)
    {
        $this->itemType = $itemType;
    }

    /**
     * @return SchemaNamedTypeInterface
     */
    public function getItemType(): SchemaNamedTypeInterface
    {
        return $this->itemType;
    }

    /**
     * @param SchemaNamedTypeInterface $newItemType
     */
    public function setItemType(SchemaTypeInterface $newItemType): void
    {
        $this->itemType = $newItemType;
    }

    /**
     * @param mixed $data
     * @param TypeRegistry $typeRegistry
     * @return array
     * @throws DataWasWrongTypeException
     */
    public function getValueFromData(mixed $data, TypeRegistry $typeRegistry): array
    {
        if (!is_array($data)) {
            throw new DataWasWrongTypeException('array', $data);
        }

        return array_map(function ($itemData) use ($typeRegistry) {
            return $this->itemType->getValueFromData($itemData, $typeRegistry);
        }, $data);
    }

    /**
     * @param mixed $value
     * @param TypeRegistry $typeRegistry
     * @return array
     * @throws ValueWasWrongTypeException
     */
    public function getDataFromValue(mixed $value, TypeRegistry $typeRegistry): array
    {
        if (!is_array($value)) {
            throw new ValueWasWrongTypeException('array', $value);
        }

        return array_map(function ($itemValue) use ($typeRegistry) {
            return $this->itemType->getDataFromValue($itemValue, $typeRegistry);
        }, $value);
    }
}
