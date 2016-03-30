<?php declare(strict_types=1);
namespace Schale\Schema\Type;

use Schale\Interfaces\Schema\SchemaNamedTypeInterface;
use Schale\Schema\TypeRegistry;
use DomainException;

class Placeholder implements SchemaNamedTypeInterface
{
    protected $typeName;

    public function __construct($typeName)
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
}
