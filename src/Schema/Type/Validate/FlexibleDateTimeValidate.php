<?php declare(strict_types=1);

namespace Shale\Schema\Type\Validate;

use DateTime;
use Shale\Util\DateTimeHelper;

class FlexibleDateTimeValidate implements Validator
{
    /**
     * Validate a date
     *
     * @param $date
     * @return bool
     */
    public static function validate($date): bool
    {
        $dateTime = (new DateTimeHelper)->getDateTime($date);

        return ($dateTime instanceof DateTime);
    }
}
