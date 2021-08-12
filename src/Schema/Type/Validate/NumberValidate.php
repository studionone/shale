<?php

declare(strict_types=1);

namespace Shale\Schema\Type\Validate;

use Shale\Interfaces\Schema\ValidatorInterface;

/**
 * Class NumberValidate
 *
 * @package Shale\Schema\Type\Validate
 */
class NumberValidate implements ValidatorInterface
{
    /**
     * @inheritDoc
     */
    public static function validate(mixed $data): bool
    {
        return (is_int($data) || is_float($data));
    }
}
