<?php

declare(strict_types=1);

namespace Shale\Schema;

use Exception;
use ReflectionClass;
use ReflectionException;
use Shale\Schema\Type\BaseObject;
use Shale\Interfaces\Schema\SchemaNamedTypeInterface;

/**
 * Class TypeRegistry
 *
 * @package Shale\Schema
 */
class TypeRegistry
{
    // Maps type names to schemas
    protected $typesByName = [];
    protected $typesByModelFqcn = [];

    /**
     * TypeRegistry constructor.
     *
     * @param mixed ...$primitiveTypes
     */
    public function __construct(...$primitiveTypes)
    {
        foreach ($primitiveTypes as $primitiveType) {
            $this->registerType($primitiveType);
        }
    }

    /**
     * @param SchemaNamedTypeInterface $schemaType
     */
    public function registerType(SchemaNamedTypeInterface $schemaType)
    {
        $name = $schemaType->getName();
        $this->typesByName[$name] = $schemaType;

        // XXX TODO Make this less ugly
        if ($schemaType instanceof BaseObject) {
            $this->typesByModelFqcn[$schemaType->getModelFqcn()] = $schemaType;
        }
    }

    /**
     * @param string $typeName
     * @return SchemaNamedTypeInterface
     * @throws Exception
     */
    public function getType(string $typeName): SchemaNamedTypeInterface
    {
        if (!isset($this->typesByName[$typeName])) {
            // XXX TODO This needs its own exception type
            throw new Exception("Type '{$typeName}' not found.");
        }

        return $this->typesByName[$typeName];
    }

    /**
     * @param string $modelFqcn
     * @return SchemaNamedTypeInterface
     */
    public function getTypeByModelFqcn(string $modelFqcn): SchemaNamedTypeInterface
    {
        return $this->typesByModelFqcn[$modelFqcn];
    }

    /**
     * @param $modelInstance
     * @return SchemaNamedTypeInterface
     * @throws ReflectionException
     */
    public function getTypeByModelInstance($modelInstance): SchemaNamedTypeInterface
    {
        $reflClass = new ReflectionClass($modelInstance);
        $modelFqcn = $reflClass->getName();

        return $this->getTypeByModelFqcn($modelFqcn);
    }

    /**
     * @return array
     */
    public function getAllTypes(): array
    {
        return $this->typesByName;
    }
}
