<?php

declare(strict_types=1);

namespace Shale\Annotation;

use ReflectionClass;
use ReflectionProperty;
use ReflectionException;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use Shale\Exception\Schema\LoadSchemaException;
use Shale\Interfaces\Annotation\ClassSchemaAnnotationInterface;
use Shale\Interfaces\Annotation\PropertySchemaAnnotationInterface;
use Shale\Interfaces\Schema\SchemaTypeInterface;
use Shale\Schema\Type\BaseObject;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Model implements ClassSchemaAnnotationInterface
{
    /** @var string */
    public string $name;

    /**
     * @param string $annotatedClassPath
     * @param AnnotationReader $annotationReader
     * @return SchemaTypeInterface
     * @throws LoadSchemaException
     * @throws ReflectionException
     */
    public function giveClassSchema(
        string $annotatedClassPath,
        AnnotationReader $annotationReader
    ): SchemaTypeInterface {
        $propertySchemas = $this->getPropertySchemas(
            $annotatedClassPath,
            $annotationReader
        );

        return new BaseObject(
            $this->name,
            $annotatedClassPath,
            $propertySchemas
        );
    }

    /**
     * @param string $annotatedClassPath
     * @param AnnotationReader $annotationReader
     * @return array
     * @throws LoadSchemaException
     * @throws ReflectionException
     */
    protected function getPropertySchemas(
        string $annotatedClassPath,
        AnnotationReader $annotationReader
    ): array {
        $propertyNamesToAnnotations = $this->getPropertyAnnotations(
            $annotatedClassPath,
            $annotationReader
        );

        $propertySchemas = [];
        foreach ($propertyNamesToAnnotations as $nameInModel => $annotation) {
            $propertySchemas[] = $annotation->givePropertySchema($nameInModel);
        }

        return $propertySchemas;
    }

    /**
     * For a given class, construct a mapping of property names to
     * exactly one annotation object describing that property.
     *
     * Each annotation object will be something which implements
     * PropertySchemaAnnotationInterface.
     *
     * @param string $annotatedClassPath
     * @param AnnotationReader $annotationReader
     * @return array
     * @throws LoadSchemaException
     * @throws ReflectionException
     */
    protected function getPropertyAnnotations(
        string $annotatedClassPath,
        AnnotationReader $annotationReader
    ): array {
        $annotatedClass = new ReflectionClass($annotatedClassPath);
        $properties = $annotatedClass->getProperties();

        $propertyNamesToAnnotations = [];
        foreach ($properties as $property) {
            $annotation = $this->getPropertyAnnotationFor(
                $property,
                $annotationReader
            );

            // If we don't find a relevant annotation, don't add
            // anything to our results.
            if (!is_null($annotation)) {
                $propertyName = $property->getName();
                $propertyNamesToAnnotations[$propertyName] = $annotation;
            }
        }

        return $propertyNamesToAnnotations;
    }

    /**
     * For a given property on a class, find no more than one relevant
     * annotation.
     *
     * We only care about property annotations related to our schema
     * system (ie those implementing PropertySchemaAnnotationInterface).
     *
     * We may not find a relevant annotation for this property. This is
     * valid; in this case we return null.
     *
     * (There may be a good reason for model classes to have properties
     * which don't correspond to data in our JSON.)
     *
     * We may find more than one relevant annotation. This is invalid,
     * and in this case we throw LoadSchemaException.
     *
     * (For more than one relevant annotation, it's ambiguous what the
     * developer could mean, and likely they made a mistake, so we break
     * early to avoid unexpected behaviour.)
     *
     * @param ReflectionProperty $property
     * @param AnnotationReader $annotationReader
     * @return PropertySchemaAnnotationInterface|null
     * @throws LoadSchemaException If a model class's property has more than one annotation.
     */
    protected function getPropertyAnnotationFor(
        ReflectionProperty $property,
        AnnotationReader $annotationReader
    ): ?PropertySchemaAnnotationInterface {
        $allAnnotationsOnProperty = $annotationReader->getPropertyAnnotations($property);

        $propertyAnnotations = array_filter(
            $allAnnotationsOnProperty,
            function ($a) {
                return $a instanceof PropertySchemaAnnotationInterface;
            }
        );

        /*
         * A property should have no more than one of the "property
         * annotations" we care about.
         */
        if (count($propertyAnnotations) > 1) {
            /* There's more than one annotation we could care about on
             * this property.
             *
             * This is an error: it's ambiguous what the developer could
             * mean here (and likely the just made a mistake), so we
             * break early to avoid unexpected behaviour.
             */
            throw new LoadSchemaException(
                'The property "' . $property->name . '" on class ' .
                $property->class . ' has more than one relevant ' .
                'property annotation.'
            );
        }

        /*
         * There's *exactly one* relevant annotation on the property
         * or
         * There are no annotations we care about on this property.
         *
         * This is okay. There may be some reason for models having
         * properties which don't correspond to data in our JSON.
         */
        return $propertyAnnotations[0] ?? null;
    }
}
