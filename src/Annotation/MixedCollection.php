<?php declare(strict_types=1);
namespace Shale\Annotation;

use Shale\Interfaces\Schema\SchemaInterface;
use Shale\Interfaces\Annotation\PropertySchemaAnnotationInterface;
use Shale\Schema;

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
class MixedCollection implements PropertySchemaAnnotationInterface
{
    /**
     * The name used for this property in the transport JSON.
     *
     * The property should hold a JSON array of JSON objects.
     *
     * @var string
     */
    public $name;

    /**
     * The name of a property which specifies the model type of each
     * object.
     *
     * Every JSON object in the JSON array must have this property,
     *
     * @var string
     */
    public $typeField = null;

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
        $valueType = new Schema\Type\MixedObjectArray($this->typeField);

        return new Schema\Property(
            $this->name,
            $nameInModel,
            $valueType,
            (! $this->optional)
        );
    }
}
