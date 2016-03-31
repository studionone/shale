<?php declare(strict_types=1);
namespace Schale\Schema;

use Schale\Traits\Accessors;
use Schale\Exception\Schema\{
    DataDecodeException,
    RequiredPropertyWasNullException
};
use Schale\Interfaces\Schema\{SchemaInterface,SchemaTypeInterface};
use Schale\Schema\TypeRegistry;

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

    protected $nameInTransport;
    protected $nameInModel;
    protected $valueType;
    protected $required;

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

    public function getValueType(): SchemaTypeInterface
    {
        return $this->valueType;
    }

    public function setValueType(SchemaTypeInterface $newValueType)
    {
        $this->valueType = $newValueType;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function getRequired(): bool
    {
        return $this->isRequired();
    }

    public function setRequired(bool $newRequired)
    {
        $this->required = $newRequired;
    }

    public function createValueFromData($data, TypeRegistry $typeRegistry)
    {
        if (is_null($data)) {
            // We were given null for our data.
            //
            // This may or may not be a problem

            if ($this->required) {
                // This property is required, so null can't be given.
                throw new RequiredPropertyWasNullException(
                    $this->nameInTransport, $this->nameInModel, $data);
            } else {
                // This is an optional property.
                //
                // A bit of a strange case: we return null without even
                // using the valueType
                return null;
            }
        }

        try {
            return $this->valueType->getValueFromData($data, $typeRegistry);

        } catch (DataDecodeException $e) {
            $valueTypeName = get_class($this->valueType);
            $message = (
                'Error occurred while decoding property "' .
                $this->nameInTransport . '" (named "'. $this->nameInModel .
                '" in model) using type ' . $valueTypeName);

            // Throw a new exception which gives more context about when
            // this exception occurred.
            throw new DataDecodeException($message, $data, 0, $e);
        }
    }
}
