<?php declare(strict_types=1);
namespace Schale\Schema\Type;

use Schale\Exception\Schema\DataWasWrongTypeException;
use Schale\Interfaces\Schema\SchemaNamedTypeInterface;
use Schale\Schema\TypeRegistry;

class StringPrimitive implements SchemaNamedTypeInterface
{
    public function getName(): string
    {
        return 'string';
    }

    public function getValueFromData($data, TypeRegistry $typeRegistry)
    {
        if (! is_string($data)) {
            throw new DataWasWrongTypeException('string', $data);
        }

        return $data;
    }
}
