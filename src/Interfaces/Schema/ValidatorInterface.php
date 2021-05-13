<?php

declare(strict_types=1);

namespace Shale\Interfaces\Schema;

/**
 * Interface ValidatorInterface
 *
 * @package Shale\Interfaces\Schema
 */
interface ValidatorInterface
{
    /**
     * @param $data
     * @return bool
     */
    public static function validate($data): bool;
}
