<?php
namespace Shale\Exception\Schema\DataEncode;

use Throwable;

class ValueWasWrongTypeException extends DataEncodeException
{
    public function __construct(
        string $expectedTypeName,
        $problemValue,
        $code = 0,
        Throwable $previous = null
    ) {
        $problemValueType = gettype($problemValue);
        $message = (
            'Value given to encode was not a ' . $expectedTypeName . ', was ' .
            'instead of type ' . $problemValueType);

        parent::__construct($message, $problemValue, $code, $previous);
    }
}
