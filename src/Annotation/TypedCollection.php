<?php
namespace Schale\Annotation;

use Schale\Interfaces\Schema\SchemaInterface;
use Schale\Interfaces\Annotation\PropertySchemaAnnotationInterface;
use Schale\Schema;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 *
 * See http://doctrine-common.readthedocs.org/en/latest/reference/annotations.html
 * for documentation of the above lines and other annotations within this file.
 *
 * Remember, instances of this class are created by Doctrine's
 * Annotations library, which handles initialising the instance's public
 * attributes.
 */
class TypedCollection implements PropertySchemaAnnotationInterface
{
    /**
     * The name used for this property in the transport JSON.
     *
     * The property should hold a JSON array of JSON objects, with each
     * object having the type specified by the "type" argument.
     *
     * @var string
     */
    public $name;

    /**
     * The type of each value in the collection.
     *
     * Every value in the JSON array must be of this type.
     *
     * Can be one of the primitive types "string", "integer", or the
     * name of a model type.
     *
     * @var string
     */
    public $type;

    /**
     * If this property is optional on objects in the transport JSON.
     *
     * By default, properties are required, so this defaults to false.
     *
     * @var bool
     */
    public $optional = false;

    public function givePropertySchema($nameInModel): SchemaInterface
    {
        $arrayItemType = new Schema\Type\Placeholder($this->type);
        $propertyValueType = new Schema\Type\TypedArray($arrayItemType);

        return new Schema\Property(
            $this->name,
            $nameInModel,
            $propertyValueType,
            (! $this->optional)
        );
    }
}
