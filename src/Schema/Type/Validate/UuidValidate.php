<?php

declare(strict_types=1);

namespace Shale\Schema\Type\Validate;

use Shale\Interfaces\Schema\ValidatorInterface;

/**
 * Class UuidValidate
 *
 * @package Shale\Schema\Type\Validate
 */
class UuidValidate implements ValidatorInterface
{
    /**
     * @inheritDoc
     */
    public static function validate(mixed $data): bool
    {
        return (
            is_string($data)
            && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $data)
        );
    }
}
