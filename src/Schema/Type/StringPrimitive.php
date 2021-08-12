<?php

declare(strict_types=1);

namespace Shale\Schema\Type;

use Shale\Interfaces\Schema\SchemaNamedTypeInterface;
use Shale\Schema\Type\Validate\StringValidate;

/**
 * Class StringPrimitive
 *
 * @package Shale\Schema\Type
 */
class StringPrimitive implements SchemaNamedTypeInterface
{
    use Validator;

    /**
     * @var string[]
     */
    protected array $validators = [
        StringValidate::class,
    ];

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'string';
    }
}
