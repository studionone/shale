<?php declare(strict_types=1);

namespace Shale\Annotation;

use Doctrine\Common\Annotations\Reader as AnnotationReader;
use Shale\Annotation\Model;

/**
 * Use this annotation for "modules" from the Eclipse API.
 *
 * This behaves like Model: annotate a PHP class, set a model name which
 * can then be used as a "type" in collections and properties.
 *
 * The difference is that every Eclipse module has a property called
 * "properties". The code below generates the type of this property from
 * the module name. So if you annoate like this:
 *
 *     /**
 *      * @Annotation\EclipseModule(name="My-Module")
 *      *
 *     class MyClass
 *     {
 *         /**
 *          * @Annotation\Property(name="properties")
 *          *
 *         protected $properties;
 *
 *         // ...
 *     }
 *
 *  ...this code adds the missing 'type="My-Module-Properties"' argument
 *  in the Annotation\Property(...).
 *
 * @Annotation
 * @Target({"CLASS"})
 */
class EclipseModule extends Model
{
    /**
     * For a given class, construct a mapping of property names to
     * exactly one annotation object describing that property.
     *
     * Each annotation object will be something which implements
     * PropertySchemaAnnotationInterface.
     *
     * @param string $annotatedClassFqcn
     * @param AnnotationReader $annotationReader
     * @return array
     */
    protected function getPropertyAnnotations(
        string $annotatedClassFqcn,
        AnnotationReader $annotationReader
    ): array {
        /**
         * Use parent implementation to get mapping for explicitly
         * defined properties.
         */
        $propertyNamesToAnnotations = parent::getPropertyAnnotations(
            $annotatedClassFqcn,
            $annotationReader
        );

        /**
         * Generate the "type" value for the annotation of "properties".
         *
         * Every Eclipse module has a "properties" field. The "type" of
         * this field within our schema will also match the module's
         * name like "{module name}-Properties".
         */
        if (array_key_exists('properties', $propertyNamesToAnnotations)) {
            $propertiesAnnotation = $propertyNamesToAnnotations['properties'];
            $propertiesAnnotation->type = ($this->name . '-Properties');
        }

        return $propertyNamesToAnnotations;
    }
}
