<?php declare(strict_types=1);

namespace Shale\Schema\Type\Validate;

class BooleanValidate implements Validator {

    /**
     * @param $data
     * @return bool
     */
    public static function validate($data): bool {
        return is_bool($data) ? true: false;
    }
}
