<?php declare(strict_types=1);
namespace Shale\Schema\Type;

use Shale\Exception\Schema\DataWasWrongTypeException;
use Shale\Interfaces\Schema\SchemaNamedTypeInterface;
use Shale\Schema\TypeRegistry;

class NumberPrimitive implements SchemaNamedTypeInterface
{
    public function getName(): string
    {
        return 'number';
    }

    public function getValueFromData($data, TypeRegistry $typeRegistry)
    {
        if (!is_int($data) && !is_float($data)) {
            throw new DataWasWrongTypeException(
                'number (integer or float)', $data);
        }

        return $data;
    }
}
