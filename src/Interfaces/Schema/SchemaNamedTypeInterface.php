<?php

declare(strict_types=1);

namespace Shale\Interfaces\Schema;

/**
 * Interface SchemaNamedTypeInterface
 *
 * @package Shale\Interfaces\Schema
 */
interface SchemaNamedTypeInterface extends SchemaTypeInterface
{
    /**
     * @return string
     */
    public function getName(): string;
}
