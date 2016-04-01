<?php
namespace Schale\Exception\Schema;

use Throwable;

class DataWasWrongTypeException extends DataDecodeException
{
    public function __construct(
        string $expectedTypeName,
        $problemData,
        $code = 0,
        Throwable $previous = null
    ) {
        $problemDataType = gettype($problemData);
        $message = (
            'Data given was not a ' . $expectedTypeName . ', was instead of ' .
            'type ' . $problemDataType);

        parent::__construct($message, $problemData, $code, $previous);
    }
}
