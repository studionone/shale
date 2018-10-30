<?php declare(strict_types=1);
namespace Shale\Schema;

use ReflectionClass;
use ReflectionProperty;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use Shale\Interfaces\Annotation\ClassSchemaAnnotationInterface;
use Shale\Interfaces\Schema\SchemaTypeWithTypedItemsInterface;
use Shale\Exception\Schema\LoadSchemaException;

class Engine
{
    protected $typeRegistry;
    protected $annotationReader;

    public function __construct(
        TypeRegistry $typeRegistry,
        AnnotationReader $annotationReader
    ) {
        $this->typeRegistry = $typeRegistry;
        $this->annotationReader = $annotationReader;
    }

    protected function getModelAnnotationFor(string $classFqcn)
    {
        $modelReflClass = new ReflectionClass($classFqcn);
        $classAnnotations = $this
            ->annotationReader
            ->getClassAnnotations($modelReflClass);
        $modelAnnotations = array_filter(
            $classAnnotations,
            function ($a) {
                return $a instanceof ClassSchemaAnnotationInterface;
            }
        );

        // A class should have *exactly one* Model annotation
        if (count($modelAnnotations) < 1) {
            throw new LoadSchemaException(
                'The class ' . $classFqcn . ' has no Model annotation'
            );
        }
        if (count($modelAnnotations) > 1) {
            throw new LoadSchemaException(
                'The class ' . $classFqcn . ' has more than one Model ' .
                'annotation'
            );
        }
        return $modelAnnotations[0];
    }

    public function loadSchemaForModels($modelFqcns)
    {
        // First load all models, so we know the names of all
        // models/types within our domain.
        //
        // Any references to other types will be represented with
        // placeholder objects in our initial load of the models.
        $schemas = [];
        foreach ($modelFqcns as $fqcn) {
            $modelAnnotation = $this->getModelAnnotationFor($fqcn);

            $schema = $modelAnnotation->giveClassSchema(
                $fqcn,
                $this->annotationReader
            );

            $this->typeRegistry->registerType($schema);

            $schemas[] = $schema;
        }

        // Now that we've registered all models, re-check all of the model
        // schemas and replace placeholders with real type references.
        foreach ($schemas as $schema) {
            // This is a very naive implementation which relies on us
            // knowing every way a schema object can reference another
            // type by name:
            //
            //  - an Object has properties, which each have a
            //    valueType which may refer to another type by name.
            //
            //  - a property's valueType can also be a TypedArray, which
            //    has an itemType which may refer to another type by
            //    name.
            //
            // In the future, we may change this to use a tree-walking
            // algorithm, with each schema object implementing an
            // interface to iterate over its children.

            // Check each model property's value types.
            foreach ($schema->getAllProperties() as $schemaProperty) {
                // The property's value type may be a placeholder
                $this->replacePossiblePlaceholderAt(
                    $schemaProperty,
                    'valueType'
                );

                // The value type may be a TypedArray, in which case its
                // itemType may be a placeholder
                if (
                    $schemaProperty->getValueType() instanceof
                    SchemaTypeWithTypedItemsInterface
                ) {
                    $this->replacePossiblePlaceholderAt(
                        $schemaProperty->getValueType(),
                        'itemType'
                    );
                }
            }
        }

        // All of the models' placeholder types should be replaced with
        // real type references
    }

    protected function replacePossiblePlaceholderAt(
        $schemaObject,
        string $propertyName
    ) {
        // This doesn't use ReflectionMethod, as we may be dealing with
        // magic getters/setters (eg from our Accessors trait, or
        // something else which use ).
        $getterMethod = 'get' . ucfirst($propertyName);
        $setterMethod = 'set' . ucfirst($propertyName);

        $possiblePlaceholder = $schemaObject->$getterMethod();
        if ($possiblePlaceholder instanceof Type\Placeholder) {
            // The property *is* just a Placeholder.
            // What's the type name it refers to?
            $referencedTypeName = $possiblePlaceholder->getName();
            // Try to find the referenced type in the registry
            $realType = $this->typeRegistry->getType($referencedTypeName);
            // Replace the named attribute with the real type the
            // placeholder referred to
            $schemaObject->$setterMethod($realType);
        }
    }

    public function getAllModelSchemas(): array
    {
        return $this->typeRegistry->getAllTypes();
    }

    public function createModelInstanceFromData(
        string $modelFqcn,
        $objectData
    ) {
        $objectType = $this->typeRegistry->getTypeByModelFqcn($modelFqcn);
        // We have to provide the TypeRegistry here so MixedObjectArray
        // can look up types
        $modelInstance = $objectType->getValueFromData(
            $objectData,
            $this->typeRegistry
        );

        return $modelInstance;
    }
}
