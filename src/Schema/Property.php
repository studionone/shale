<?php

declare(strict_types=1);

namespace Shale\Schema;

use Shale\Exception\Schema\DataEncode\RequiredModelPropertyWasNullException;
use Shale\Exception\Schema\DataEncode\DataEncodeException;
use Shale\Exception\Schema\DataDecodeException;
use Shale\Exception\Schema\RequiredPropertyWasNullException;
use Shale\Interfaces\Schema\SchemaInterface;
use Shale\Interfaces\Schema\SchemaTypeInterface;
use Shale\Traits\Accessors;

/**
 *
 * @method string getNameInTransport
 * @method setNameInTransport
 * @method string getNameInModel
 * @method setNameInModel
 */
class Property implements SchemaInterface
{
    use Accessors;

    /** @var string */
    protected $nameInTransport;

    /** @var string */
    protected $nameInModel;

    /** @var SchemaTypeInterface */
    protected $valueType;

    /** @var bool */
    protected $required;

    /**
     * Property constructor.
     *
     * @param string $nameInTransport
     * @param string $nameInModel
     * @param SchemaTypeInterface $valueType
     * @param bool $required
     */
    public function __construct(
        string $nameInTransport,
        string $nameInModel,
        SchemaTypeInterface $valueType,
        bool $required = true
    ) {
        $this->nameInTransport = $nameInTransport;
        $this->nameInModel = $nameInModel;
        $this->valueType = $valueType;
        $this->required = $required;
    }

    /*
     * Manually implemented getters/setters, to enforce typing
     */

    /**
     * @return SchemaTypeInterface
     */
    public function getValueType(): SchemaTypeInterface
    {
        return $this->valueType;
    }

    /**
     * @param SchemaTypeInterface $newValueType
     */
    public function setValueType(SchemaTypeInterface $newValueType)
    {
        $this->valueType = $newValueType;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @return bool
     */
    public function getRequired(): bool
    {
        return $this->isRequired();
    }

    /**
     * @param bool $newRequired
     */
    public function setRequired(bool $newRequired)
    {
        $this->required = $newRequired;
    }

    /**
     * @param $data
     * @param TypeRegistry $typeRegistry
     * @return mixed|null
     * @throws DataDecodeException
     * @throws RequiredPropertyWasNullException
     */
    public function createValueFromData($data, TypeRegistry $typeRegistry)
    {
        // We were given null for our data
        // This may or may not be a problem
        if (is_null($data)) {
            if ($this->required) {
                // This property is required, so null can't be given.
                throw new RequiredPropertyWasNullException(
                    $this->nameInTransport,
                    $this->nameInModel,
                    $data
                );
            }

            // This is an optional property.
            // A bit of a strange case: we return null without even
            // using the valueType
            return null;
        }

        try {
            return $this->valueType->getValueFromData($data, $typeRegistry);
        } catch (DataDecodeException $exception) {
            $valueTypeName = get_class($this->valueType);
            $message = (
                'Error occurred while decoding property "' .
                $this->nameInTransport . '" (named "'. $this->nameInModel .
                '" in model) using type ' . $valueTypeName
            );

            throw new DataDecodeException($message, $data, 0, $exception);
        }
    }

    /**
     * @param $value
     * @param TypeRegistry $typeRegistry
     * @return null
     * @throws DataEncodeException
     * @throws RequiredModelPropertyWasNullException
     */
    public function createDataFromValue($value, TypeRegistry $typeRegistry)
    {
        if (is_null($value)) {
            // We were given null for our value.
            // This may or may not be a problem
            if ($this->required) {
                // This property is required, so null can't be given.
                throw new RequiredModelPropertyWasNullException(
                    $this->nameInModel,
                    $this->nameInTransport,
                    $value
                );
            }

            // This is an optional property.
            // We return null without even using the valueType. This
            // is important, as it doesn't violate the type
            // constraints of NumberPrimitive or StringPrimitive,
            // but still gives a meaningful value to transport.
            return null;
        }

        try {
            return $this->valueType->getDataFromValue($value, $typeRegistry);
        } catch (DataEncodeException $exception) {
            $message = (
                'Error occurred while encoding model instance\'s property "' .
                $this->nameInModel . '" (named "'. $this->nameInTransport .
                '" in transport) using type ' . get_class($this->valueType)
            );

            throw new DataEncodeException($message, $value, 0, $exception);
        }
    }
}
