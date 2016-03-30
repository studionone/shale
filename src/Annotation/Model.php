<?php declare(strict_types=1);
namespace Schale\Annotation;

use ReflectionClass;
use ReflectionProperty;
use Doctrine\Common\Annotations\Reader as AnnotationReader;

use Schema\Exception\Schema\LoadSchemaException;
use Schema\Interfaces\Annotation\{
    ClassSchemaAnnotationInterface,
    PropertySchemaAnnotationInterface
};
use Schema\Interfaces\Schema\SchemaTypeInterface;
use Schema\Schema;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Model implements ClassSchemaAnnotationInterface
{
    public $name;

    public function giveClassSchema(
        string $annotatedClassFqcn,
        AnnotationReader $annotationReader
    ): SchemaTypeInterface {

        $propertySchemas = $this->getPropertySchemas(
            $annotatedClassFqcn, $annotationReader);

        return new Schema\Type\Object(
            $this->name,
            $annotatedClassFqcn,
            $propertySchemas);
    }

    protected function getPropertySchemas(
        string $annotatedClassFqcn,
        AnnotationReader $annotationReader
    ): array {

        $propertyNamesToAnnotations = $this->getPropertyAnnotations(
            $annotatedClassFqcn, $annotationReader);

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
     */
    protected function getPropertyAnnotations(
        string $annotatedClassFqcn,
        AnnotationReader $annotationReader
    ): array {
        $reflAnnotatedClass = new ReflectionClass($annotatedClassFqcn);
        $reflProperties = $reflAnnotatedClass->getProperties();

        $propertyNamesToAnnotations = [];
        foreach ($reflProperties as $reflProperty) {
            $annotation = $this->getPropertyAnnotationFor(
                $reflProperty, $annotationReader);
            // If we don't find a relevant annotation, don't add
            // anything to our results.
            if (!is_null($annotation)) {
                $propertyName = $reflProperty->getName();
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
     * @return null|PropertySchemaAnnotationInterface
     * @throws Expore\Exception\Schema\LoadSchemaException If a model class's property has more than one annotation.
     */
    protected function getPropertyAnnotationFor(
        ReflectionProperty $reflProperty,
        AnnotationReader $annotationReader
    ) {
        $allAnnotationsOnProperty = $annotationReader
            ->getPropertyAnnotations($reflProperty);
        $propertyAnnotations = array_filter(
            $allAnnotationsOnProperty,
            function ($a) {
                return $a instanceof PropertySchemaAnnotationInterface;
            }
        );

        /* A property should have no more than one of the "property
         * annotations" we care about.
         */
        if (count($propertyAnnotations) < 1) {
            /* There are no annotations we care about on this property.
             *
             * This is okay. There may be some reason for models having
             * properties which don't correspond to data in our JSON.
             */
            return null;
        }

        if (count($propertyAnnotations) > 1) {
            /* There's more than one annotation we could care about on
             * this property.
             *
             * This is an error: it's ambiguous what the developer could
             * mean here (and likely the just made a mistake), so we
             * break early to avoid unexpected behaviour.
             */
            throw new LoadSchemaException(
                'The property "' . $reflProperty->name . '" on class ' .
                $reflProperty->class . ' has more than one relevant ' .
                'property annotation.');
        }

        // There's *exactly one* relevant annotation on the property
        return $propertyAnnotations[0];
    }

}
