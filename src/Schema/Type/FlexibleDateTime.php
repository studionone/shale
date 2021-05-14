<?php

declare(strict_types=1);

namespace Shale\Schema\Type;

use Shale\Interfaces\Schema\SchemaNamedTypeInterface;
use Shale\Schema\Type\Validate\FlexibleDateTimeValidate;

/**
 * Class FlexibleDateTime
 *
 * @package Shale\Schema\Type
 */
class FlexibleDateTime implements SchemaNamedTypeInterface
{
    use Validator;

    /**
     * @var string[]
     */
    protected $validators = [
        FlexibleDateTimeValidate::class,
    ];

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'flexibleDateTime';
    }
}
