<?php

declare(strict_types=1);

namespace Shale\Schema\Type;

use Shale\Interfaces\Schema\SchemaNamedTypeInterface;
use Shale\Schema\Type\Validate\BooleanValidate;

/**
 * Class BooleanPrimitive
 *
 * @package Shale\Schema\Type
 */
class BooleanPrimitive implements SchemaNamedTypeInterface
{
    use Validator;

    /**
     * @var string[]
     */
    protected $validators = [
        BooleanValidate::class,
    ];

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'boolean';
    }
}
