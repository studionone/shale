<?php

declare(strict_types=1);

namespace Shale\Annotation;

use Shale\Interfaces\Schema\SchemaInterface;
use Shale\Interfaces\Annotation\PropertySchemaAnnotationInterface;
use Shale\Schema\Property as ShaleSchemaProperty;
use Shale\Schema\Type\Placeholder;

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
class Property implements PropertySchemaAnnotationInterface
{
    /**
     * The name used for this property in the transport JSON.
     *
     * @var string
     */
    public $name;

    /**
     * The type of this property in the transport JSON.
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

    /**
     * @param string $nameInModel
     * @return SchemaInterface
     */
    public function givePropertySchema(string $nameInModel): SchemaInterface
    {
        $valueType = new Placeholder($this->type);

        return new ShaleSchemaProperty(
            $this->name,
            $nameInModel,
            $valueType,
            !$this->optional
        );
    }
}
