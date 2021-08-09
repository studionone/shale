<?php

declare(strict_types=1);

namespace Shale\Schema\Type;

use Shale\Interfaces\Schema\SchemaNamedTypeInterface;
use Shale\Schema\TypeRegistry;
use DomainException;

/**
 * Class Placeholder
 *
 * @package Shale\Schema\Type
 */
class Placeholder implements SchemaNamedTypeInterface
{
    /** @var string */
    protected string $typeName;

    /**
     * Placeholder constructor.
     *
     * @param string $typeName
     */
    public function __construct(string $typeName)
    {
        $this->typeName = $typeName;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->typeName;
    }

    /**
     * @param mixed $data
     * @param TypeRegistry $typeRegistry
     * @return mixed
     */
    public function getValueFromData(mixed $data, TypeRegistry $typeRegistry): mixed
    {
        throw new DomainException('This class should not exist after Schema has been mapped');
    }

    /**
     * @param $value
     * @param TypeRegistry $typeRegistry
     */
    public function getDataFromValue($value, TypeRegistry $typeRegistry): mixed
    {
        throw new DomainException('This class should not exist after Schema has been mapped');
    }
}
