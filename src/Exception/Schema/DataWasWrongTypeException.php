<?php declare(strict_types=1);

namespace Shale\Exception\Schema;

use Throwable;

class DataWasWrongTypeException extends DataDecodeException
{
    public function __construct(
        string $expectedTypeName,
        $problemData,
        int $code = 0,
        Throwable $previous = null
    ) {
        $problemDataType = gettype($problemData);
        $message = (
            'Data given to decode was not a ' . $expectedTypeName . ', was ' .
            'instead of type ' . $problemDataType);

        parent::__construct($message, $problemData, $code, $previous);
    }
}
