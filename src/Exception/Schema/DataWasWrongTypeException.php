<?php

declare(strict_types=1);

namespace Shale\Exception\Schema;

use Throwable;

/**
 * Class DataWasWrongTypeException
 *
 * @package Shale\Exception\Schema
 */
class DataWasWrongTypeException extends DataDecodeException
{
    /**
     * DataWasWrongTypeException constructor.
     *
     * @param string $expectedTypeName
     * @param $problemData
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $expectedTypeName,
        $problemData,
        int $code = 0,
        Throwable $previous = null
    ) {
        $message = (
            'Data given to decode was not a ' . $expectedTypeName . ', was ' .
            'instead of type ' . gettype($problemData)
        );

        parent::__construct($message, $problemData, $code, $previous);
    }
}
