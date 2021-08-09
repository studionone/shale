<?php

declare(strict_types=1);

namespace Shale\Schema\Type\Validate;

use Shale\Interfaces\Schema\ValidatorInterface;

/**
 * Class BooleanValidate
 *
 * @package Shale\Schema\Type\Validate
 */
class BooleanValidate implements ValidatorInterface
{
    /**
     * @inheritDoc
     */
    public static function validate(mixed $data): bool
    {
        return is_bool($data);
    }
}
