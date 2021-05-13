<?php

declare(strict_types=1);

namespace Shale\Schema\Type\Validate;

use Shale\Interfaces\Schema\ValidatorInterface;

/**
 * Class StringValidate
 *
 * @package Shale\Schema\Type\Validate
 */
class StringValidate implements ValidatorInterface
{
    /**
     * @inheritDoc
     */
    public static function validate($data): bool
    {
        return is_string($data);
    }
}
