<?php declare(strict_types=1);

namespace Shale\Schema\Type;

use Shale\Exception\Schema\DataWasWrongTypeException;
use Shale\Exception\Schema\DataDecodeException;
use Shale\Exception\Schema\RequiredPropertyMissingException;
use Shale\Exception\Schema\DataEncode\RequiredModelPropertyWasNullException;
use Shale\Interfaces\Schema\SchemaInterface;
use Shale\Interfaces\Schema\SchemaNamedTypeInterface;
use Shale\Schema\TypeRegistry;
use ReflectionClass;
use stdClass;

class BaseObject implements SchemaNamedTypeInterface
{
    protected $name;
    protected $modelFqcn;
    protected $properties;

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
     */
    protected function processProperties(array $propertiesAsIndexedArray): array
    {
        $propertiesByName = [];

        foreach ($propertiesAsIndexedArray as $property) {
            $propertiesByName[$property->getNameInTransport()] = $property;
        }

        return $propertiesByName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getModelFqcn(): string
    {
        return $this->modelFqcn;
    }

    public function getAllProperties(): array
    {
        return $this->properties;
    }

    public function getPropertyByNameInTransport(
        string $nameInTransport
    ): SchemaInterface {
        return $this->properties[$nameInTransport];
    }

    public function getValueFromData($data, TypeRegistry $typeRegistry)
    {
        if (! $data instanceof stdClass) {
            throw new DataWasWrongTypeException(
                'object (stdclass)',
                $data
            );
        }

        $reflModelClass = new ReflectionClass($this->modelFqcn);
        $modelInstance = $reflModelClass->newInstance();

        foreach ($this->properties as $schemaProperty) {
            $nameInTransport = $schemaProperty->getNameInTransport();

            if (! property_exists($data, $nameInTransport)) {
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
     *
     *
     * This uses the schema information which *this instance* holds to
     * transform the given object instance into serializable data.
     *
     * The resulting serializable data should be a stdclass instance,
     * with only other stdclass instances or PHP primitives as
     * attributes. This is a form suitable for, eg giving to
     * json_encode() to produce transportable byte strings.
     */
    public function getDataFromValue($value, TypeRegistry $typeRegistry)
    {
        $reflModelClass = new ReflectionClass($value);

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
