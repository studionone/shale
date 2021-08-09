<?php

declare(strict_types=1);

namespace Shale\Schema\Type;

use Shale\Interfaces\Schema\SchemaNamedTypeInterface;
use Shale\Schema\Type\Validate\UuidValidate;

/**
 * Class Uuid
 *
 * @package Shale\Schema\Type
 */
class Uuid implements SchemaNamedTypeInterface
{
    use Validator;

    /**
     * @var string[]
     */
    protected array $validators = [
        UuidValidate::class,
    ];

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'uuid';
    }
}
