<?php

declare(strict_types=1);

namespace Shale\Schema\Type;

use Shale\Interfaces\Schema\SchemaNamedTypeInterface;
use Shale\Schema\Type\Validate\NumberValidate;

/**
 * Class NumberPrimitive
 *
 * @package Shale\Schema\Type
 */
class NumberPrimitive implements SchemaNamedTypeInterface
{
    use Validator;

    /**
     * @var string[]
     */
    protected array $validators = [
        NumberValidate::class,
    ];

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'number';
    }
}
