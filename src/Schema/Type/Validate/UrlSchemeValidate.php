<?php

declare(strict_types=1);

namespace Shale\Schema\Type\Validate;

use Shale\Interfaces\Schema\ValidatorInterface;

/**
 * Class UrlSchemeValidate
 *
 * @package Shale\Schema\Type\Validate
 */
class UrlSchemeValidate implements ValidatorInterface
{
    /**
     * @inheritDoc
     */
    public static function validate($data): bool
    {
        return preg_match('/^(http|https)://', $data) !== false;
    }
}
