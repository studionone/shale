<?php declare(strict_types=1);
namespace Schale\Interfaces\Schema;

interface SchemaNamedTypeInterface extends SchemaTypeInterface
{
    public function getName(): string;
}
