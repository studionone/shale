<?php

declare(strict_types=1);

namespace Shale\Util;

use DateTime;
use Exception;

/**
 * Class DateTimeHelper
 *
 * @package Shale\Util
 */
class DateTimeHelper
{
    /**
     * Get DateTime object from value
     *
     * @param string|int $date
     * @param null|string $format
     * @return DateTime|null
     */
    public function getDateTime(string|int $date, ?string $format = null): ?DateTime
    {
        if ($this->isTimestamp($date)) {
            return DateTime::createFromFormat('U', (string) $date);
        }

        if (!empty($format)) {
            return $this->dateFromFormat($date, $format);
        }

        return $this->guessFormat($date);
    }

    /**
     * Check if value is a timestamp
     *
     * @param string|int $date Possible timestamp value
     * @return bool
     */
    public function isTimestamp(string|int $date): bool
    {
        if (!is_numeric($date)) {
            return false;
        }

        $timestamp = (int) $date;

        return ((string) $timestamp === (string) $date
            && $timestamp >= 0
            && $timestamp <= PHP_INT_MAX
        );
    }

    /**
     * Try to automatically resolve a format
     *
     * @param string|int $date
     * @return DateTime|null
     */
    public function guessFormat(string|int $date): ?DateTime
    {
        if (!is_string($date)) {
            return null;
        }

        try {
            return new DateTime($date);
        } catch (Exception $exception) {
            $malformedFormats = [
                'd/m/Y H:i:s',
                'Y/d/m H:i:s',
                'm-d-Y H:i:s',
                'Y-d-m H:i:s',
            ];

            return $this->dateFromFormats($date, $malformedFormats);
        }
    }

    /**
     * Get DateTime object from a specified format
     *
     * @param string|int $date
     * @param null|string $format
     * @return DateTime|null
     */
    public function dateFromFormat(string|int $date, ?string $format = null): ?DateTime
    {
        $formats = !empty($format) ? [$format] : [];

        return $this->dateFromFormats($date, $formats);
    }

    /**
     * Get DateTime object from a list of specified formats
     *
     * @param string|int $date
     * @param array $formats List of formats to check
     * @return DateTime|null
     */
    public function dateFromFormats(string|int $date, array $formats = []): ?DateTime
    {
        if (!is_string($date)) {
            return null;
        }

        if (empty($formats)) {
            return $this->guessFormat($date);
        }

        foreach ($formats as $format) {
            $dateTime = DateTime::createFromFormat($format, $date);

            if ($dateTime instanceof DateTime) {
                return $dateTime;
            }
        }

        return null;
    }
}
