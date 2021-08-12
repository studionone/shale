<?php

declare(strict_types=1);

namespace Shale\Annotation;

use Shale\Interfaces\Schema\SchemaInterface;
use Shale\Interfaces\Annotation\PropertySchemaAnnotationInterface;
use Shale\Schema\Property;
use Shale\Schema\Type\{
    Placeholder,
    TypedArray
};

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
    public string $name;

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
    public string $type;

    /**
     * If this property is optional on objects in the transport JSON.
     *
     * By default, properties are required, so this defaults to false.
     *
     * @var bool
     */
    public bool $optional = false;

    /**
     * @param string $nameInModel
     * @return SchemaInterface
     */
    public function givePropertySchema(string $nameInModel): SchemaInterface
    {
        $arrayItemType = new Placeholder($this->type);
        $propertyValueType = new TypedArray($arrayItemType);

        return new Property(
            $this->name,
            $nameInModel,
            $propertyValueType,
            !$this->optional
        );
    }
}
