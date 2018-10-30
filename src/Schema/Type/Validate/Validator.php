<?php declare(strict_types=1);

namespace Shale\Schema\Type\Validate;

interface Validator
{
    public static function validate($data): bool ;
}
