<?php declare(strict_types=1);
namespace Schale\Schema\Type;

use Schale\Exception\Schema\DataWasWrongTypeException;
use Schale\Interfaces\Schema\SchemaTypeInterface;
use Schale\Schema\TypeRegistry;
use Schale\Traits\Accessors;

/**
 * @method string getTypeFieldName
 * @method setTypeFieldName
 */
class MixedObjectArray implements SchemaTypeInterface
{
    use Accessors;

    protected $typeFieldName;

    public function __construct(string $typeFieldName) {
        $this->typeFieldName = $typeFieldName;
    }

    public function getValueFromData($data, TypeRegistry $typeRegistry)
    {
        if (! is_array($data)) {
            throw new DataWasWrongTypeException('array', $data);
        }

        $contents = [];

        foreach ($data as $itemData) {
            // XXX TODO Add checking here so we give better exceptions
            // when the type field doesn't exist or decoding with the
            // specified type fails
            $itemTypeName = $itemData->{$this->typeFieldName};
            $itemType = $typeRegistry->getType($itemTypeName);
            $item = $itemType
                ->getValueFromData($itemData, $typeRegistry);
            $contents[] = $item;
        }

        return $contents;
    }
}
