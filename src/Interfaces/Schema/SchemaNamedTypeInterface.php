<?php declare(strict_types=1);
namespace Shale\Interfaces\Schema;

interface SchemaNamedTypeInterface extends SchemaTypeInterface
{
    public function getName(): string;
}
