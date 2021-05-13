<?php

declare(strict_types=1);

namespace Shale\Schema\Type;

use Shale\Exception\Schema\DataWasWrongTypeException;
use Shale\Exception\Schema\DataEncode\ValueWasWrongTypeException;
use Shale\Interfaces\Schema\SchemaTypeInterface;
use Shale\Schema\TypeRegistry;
use Shale\Traits\Accessors;

/**
 * @method string getTypeFieldName
 * @method setTypeFieldName
 */
class MixedObjectArray implements SchemaTypeInterface
{
    use Accessors;

    /** @var string */
    protected $typeFieldName;

    /**
     * MixedObjectArray constructor.
     *
     * @param string $typeFieldName
     */
    public function __construct(string $typeFieldName)
    {
        $this->typeFieldName = $typeFieldName;
    }

    /**
     * @param mixed $data
     * @param TypeRegistry $typeRegistry
     * @return array
     * @throws DataWasWrongTypeException
     */
    public function getValueFromData($data, TypeRegistry $typeRegistry): array
    {
        if (!is_array($data)) {
            throw new DataWasWrongTypeException('array', $data);
        }

        return array_map(function ($itemData) use ($typeRegistry) {
            // XXX TODO Add checking here so we give better exceptions
            // when the type field doesn't exist or decoding with the
            // specified type fails
            $itemTypeName = $itemData->{$this->typeFieldName};
            $itemType = $typeRegistry->getType($itemTypeName);

            return $itemType->getValueFromData($itemData, $typeRegistry);
        }, $data);
    }

    /**
     * @param $value
     * @param TypeRegistry $typeRegistry
     * @return array
     * @throws ValueWasWrongTypeException
     */
    public function getDataFromValue($value, TypeRegistry $typeRegistry): array
    {
        if (!is_array($value)) {
            throw new ValueWasWrongTypeException('array', $value);
        }

        return array_map(function ($itemValue) use ($typeRegistry) {
            $itemSchemaType = $typeRegistry->getTypeByModelInstance($itemValue);
            $item = $itemSchemaType->getDataFromValue($itemValue, $typeRegistry);

            // After we create the data object, we need to add a type
            // field to describe the object's type
            $item->{$this->typeFieldName} = $itemSchemaType->getName();

            return $item;
        }, $value);
    }
}
