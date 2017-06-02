<?php declare(strict_types=1);
namespace Shale\Schema\Type;

use Shale\Exception\Schema\DataWasWrongTypeException;
use Shale\Interfaces\Schema\SchemaNamedTypeInterface;
use Shale\Schema\TypeRegistry;

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
