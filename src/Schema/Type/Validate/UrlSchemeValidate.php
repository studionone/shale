<?php declare(strict_types=1);

namespace Shale\Schema\Type\Validate;

class UrlSchemeValidate implements Validator
{

    /**
     * @param $data
     * @return bool
     */
    public static function validate($data): bool
    {
        if (! preg_match("/^(http|https)://", $data)) {
            return false;
        }

        return true;
    }
}
