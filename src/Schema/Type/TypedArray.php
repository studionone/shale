<?php declare(strict_types=1);

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
    protected $itemType;

    public function __construct(SchemaNamedTypeInterface $itemType)
    {
        $this->itemType = $itemType;
    }

    /**
     * @return SchemaTypeInterface
     */
    public function getItemType(): SchemaTypeInterface
    {
        return $this->itemType;
    }

    /**
     * @param SchemaTypeInterface $newItemType
     */
    public function setItemType(SchemaTypeInterface $newItemType)
    {
        $this->itemType = $newItemType;
    }

    /**
     * @param mixed $data
     * @param TypeRegistry $typeRegistry
     * @return array
     * @throws DataWasWrongTypeException
     */
    public function getValueFromData($data, TypeRegistry $typeRegistry)
    {
        if (! is_array($data)) {
            throw new DataWasWrongTypeException('array', $data);
        }

        $contents = [];

        foreach ($data as $itemData) {
            $item = $this
                ->itemType
                ->getValueFromData($itemData, $typeRegistry);
            $contents[] = $item;
        }

        return $contents;
    }

    /**
     * @param mixed $value
     * @param TypeRegistry $typeRegistry
     * @return array
     * @throws ValueWasWrongTypeException
     */
    public function getDataFromValue($value, TypeRegistry $typeRegistry)
    {
        if (! is_array($value)) {
            throw new ValueWasWrongTypeException('array', $value);
        }

        $contents = [];

        foreach ($value as $itemValue) {
            $item = $this
                ->itemType
                ->getDataFromValue($itemValue, $typeRegistry);
            $contents[] = $item;
        }

        return $contents;
    }
}
