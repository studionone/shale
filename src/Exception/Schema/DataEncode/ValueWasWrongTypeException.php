<?php

declare(strict_types=1);

namespace Shale\Exception\Schema\DataEncode;

use Throwable;

/**
 * Class ValueWasWrongTypeException
 *
 * @package Shale\Exception\Schema\DataEncode
 */
class ValueWasWrongTypeException extends DataEncodeException
{
    /**
     * ValueWasWrongTypeException constructor.
     *
     * @param string $expectedTypeName
     * @param $problemValue
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $expectedTypeName,
        $problemValue,
        int $code = 0,
        Throwable $previous = null
    ) {
        $message = (
            'Value given to encode was not a ' . $expectedTypeName . ', was ' .
            'instead of type ' . gettype($problemValue)
        );

        parent::__construct($message, $problemValue, $code, $previous);
    }
}
