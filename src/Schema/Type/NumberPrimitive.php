<?php declare(strict_types=1);
namespace Schale\Schema\Type;

use Schale\Exception\Schema\DataWasWrongTypeException;
use Schale\Interfaces\Schema\SchemaNamedTypeInterface;
use Schale\Schema\TypeRegistry;

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
