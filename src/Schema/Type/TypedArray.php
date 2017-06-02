<?php declare(strict_types=1);
namespace Shale\Schema\Type;

use Shale\Exception\Schema\DataWasWrongTypeException;
use Shale\Interfaces\Schema\SchemaTypeInterface;
use Shale\Interfaces\Schema\SchemaTypeWithTypedItemsInterface;
use Shale\Schema\TypeRegistry;

/**
 *
 *
 * @method SchemaTypeInterface getItemType
 * @method setItemType
 */
class TypedArray implements SchemaTypeWithTypedItemsInterface
{
    protected $itemType;
    
    public function __construct($itemType) {
        $this->itemType = $itemType;
    }

    /*
     * Manually implemented getters/setters, to enforce typing
     */

    public function getItemType(): SchemaTypeInterface
    {
        return $this->itemType;
    }

    public function setItemType(SchemaTypeInterface $newItemType)
    {
        $this->itemType = $newItemType;
    }

    public function getValueFromData($data, TypeRegistry $typeRegistry)
    {
        if (! is_array($data)) {
            throw new DataWasWrongTypeException('array', $data);
        }

        $contents = [];

        foreach ($data as $itemData) {
            $item = $this
                ->itemType
                ->getValueFromData($itemData, $typeRegistry);
            $contents[] = $item;
        }

        return $contents;
    }
}
