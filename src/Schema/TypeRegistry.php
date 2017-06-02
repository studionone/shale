<?php declare(strict_types=1);
namespace Shale\Schema;

use Shale\Interfaces\Schema\SchemaNamedTypeInterface;

class TypeRegistry
{
    // Maps type names to schemas
    protected $typesByName = [];
    protected $typesByModelFqcn = [];

    public function __construct(...$primitiveTypes)
    {
        foreach ($primitiveTypes as $primitiveType) {
            $this->registerType($primitiveType);
        }
    }

    public function registerType(SchemaNamedTypeInterface $schemaType)
    {
        $name = $schemaType->getName();
        $this->typesByName[$name] = $schemaType;

        // XXX TODO Make this less ugly
        if ($schemaType instanceof Type\Object) {
            $this->typesByModelFqcn[$schemaType->getModelFqcn()] = $schemaType;
        }
    }

    public function getType(string $typeName): SchemaNamedTypeInterface
    {
        if (! array_key_exists($typeName, $this->typesByName)) {
            // XXX TODO This needs its own exception type
            throw new \Exception('No type with that name');
        }

        return $this->typesByName[$typeName];
    }

    public function getTypeByModelFqcn(
        string $modelFqcn
    ): SchemaNamedTypeInterface {
        return $this->typesByModelFqcn[$modelFqcn];
    }

    public function getAllTypes(): array
    {
        return $this->typesByName;
    }
}
