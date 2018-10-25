<?php declare(strict_types=1);

namespace Shale\Schema\Type\Validate;

class NumberValidate implements Validator {

    /**
     * @param $data
     * @return bool
     */
    public static function validate($data): bool {
        if (!is_int($data) && !is_float($data)) {
            return false;
        }

        return true;
    }
}
