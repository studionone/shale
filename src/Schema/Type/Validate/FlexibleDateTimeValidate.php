<?php

declare(strict_types=1);

namespace Shale\Schema\Type\Validate;

use DateTime;
use Shale\Interfaces\Schema\ValidatorInterface;
use Shale\Util\DateTimeHelper;

/**
 * Class FlexibleDateTimeValidate
 *
 * @package Shale\Schema\Type\Validate
 */
class FlexibleDateTimeValidate implements ValidatorInterface
{
    /**
     * @inheritDoc
     */
    public static function validate($data): bool
    {
        $dateTime = (new DateTimeHelper)->getDateTime($data);

        return ($dateTime instanceof DateTime);
    }
}
