<?php declare(strict_types=1);
namespace Shale\Schema\Type;

use Shale\Exception\Schema\DataWasWrongTypeException;
use Shale\Interfaces\Schema\SchemaTypeInterface;
use Shale\Schema\TypeRegistry;
use Shale\Traits\Accessors;

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
