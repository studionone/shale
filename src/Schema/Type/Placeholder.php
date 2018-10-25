<?php declare(strict_types=1);

namespace Shale\Schema\Type;

use Shale\Interfaces\Schema\SchemaNamedTypeInterface;
use Shale\Schema\TypeRegistry;
use DomainException;

class Placeholder implements SchemaNamedTypeInterface
{
    protected $typeName;

    public function __construct(string $typeName)
    {
        $this->typeName = $typeName;
    }

    public function getName(): string
    {
        return $this->typeName;
    }

    public function getValueFromData($data, TypeRegistry $typeRegistry)
    {
        throw new DomainException('This class should not exist after Schema has been mapped');
    }

    public function getDataFromValue($value, TypeRegistry $typeRegistry)
    {
        throw new DomainException('This class should not exist after Schema has been mapped');
    }
}
