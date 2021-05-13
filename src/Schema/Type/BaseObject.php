<?php

declare(strict_types=1);

namespace Shale\Schema\Type;

use Shale\Exception\Schema\DataWasWrongTypeException;
use Shale\Exception\Schema\RequiredPropertyMissingException;
use Shale\Interfaces\Schema\SchemaInterface;
use Shale\Interfaces\Schema\SchemaNamedTypeInterface;
use Shale\Schema\TypeRegistry;
use ReflectionClass;
use ReflectionException;
use stdClass;

/**
 * Class BaseObject
 *
 * @package Shale\Schema\Type
 */
class BaseObject implements SchemaNamedTypeInterface
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $modelFqcn;

    /** @var array */
    protected $properties;

    /**
     * BaseObject constructor.
     *
     * @param string $name
     * @param string $modelFqcn
     * @param array $properties
     */
    public function __construct(
        string $name,
        string $modelFqcn,
        array $properties
    ) {
        $this->name = $name;
        $this->modelFqcn = $modelFqcn;
        $this->properties = $this->processProperties($properties);
    }

    /**
     * Process the given properties array to be keyed by
     * $property->nameInTransport.
     *
     * @param array $propertiesAsIndexedArray
     * @return array
     */
    protected function processProperties(array $propertiesAsIndexedArray): array
    {
        $propertiesByName = [];

        foreach ($propertiesAsIndexedArray as $property) {
            $propertiesByName[$property->getNameInTransport()] = $property;
        }

        return $propertiesByName;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getModelFqcn(): string
    {
        return $this->modelFqcn;
    }

    /**
     * @return array
     */
    public function getAllProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param string $nameInTransport
     * @return SchemaInterface
     */
    public function getPropertyByNameInTransport(string $nameInTransport): SchemaInterface
    {
        return $this->properties[$nameInTransport];
    }

    /**
     * @param mixed $data
     * @param TypeRegistry $typeRegistry
     * @return mixed|object
     * @throws DataWasWrongTypeException
     * @throws RequiredPropertyMissingException
     * @throws ReflectionException
     */
    public function getValueFromData($data, TypeRegistry $typeRegistry)
    {
        if (!$data instanceof stdClass) {
            throw new DataWasWrongTypeException('object (stdclass)', $data);
        }

        $reflModelClass = new ReflectionClass($this->modelFqcn);
        $modelInstance = $reflModelClass->newInstance();

        foreach ($this->properties as $schemaProperty) {
            $nameInTransport = $schemaProperty->getNameInTransport();

            if (!property_exists($data, $nameInTransport)) {
                if ($schemaProperty->isRequired()) {
                    throw new RequiredPropertyMissingException(
                        $nameInTransport,
                        $schemaProperty->getNameInModel(),
                        $data
                    );
                }
                // Property is not required.
                //
                // This isn't an error. Just continue.
            } else {
                $propertyData = $data->$nameInTransport;

                $propertyValue = $schemaProperty
                    ->createValueFromData($propertyData, $typeRegistry);

                $setMethod = 'set' . ucfirst($schemaProperty->getNameInModel());
                $modelInstance->$setMethod($propertyValue);
            }
        }

        return $modelInstance;
    }

    /**
     * This uses the schema information which *this instance* holds to
     * transform the given object instance into serializable data.
     *
     * The resulting serializable data should be a stdclass instance,
     * with only other stdclass instances or PHP primitives as
     * attributes. This is a form suitable for, eg giving to
     * json_encode() to produce transportable byte strings.
     *
     * @param $value
     * @param TypeRegistry $typeRegistry
     * @return stdClass
     */
    public function getDataFromValue($value, TypeRegistry $typeRegistry): stdClass
    {
        $data = new stdClass();

        foreach ($this->properties as $schemaProperty) {
            $getMethod = 'get' . ucfirst($schemaProperty->getNameInModel());
            $propertyValue = $value->$getMethod();

            $propertyData = $schemaProperty->createDataFromValue(
                $propertyValue,
                $typeRegistry
            );

            $nameInTransport = $schemaProperty->getNameInTransport();
            $data->$nameInTransport = $propertyData;
        }

        return $data;
    }
}
