<?php declare(strict_types=1);

namespace Shale\Exception\Schema\DataEncode;

use Throwable;
use Shale\Exception\Schema\SchemaException;

class DataEncodeException extends SchemaException
{
    public $problemValue;

    public function __construct(
        string $message = "",
        $problemValue = null,
        int $code = 0,
        Throwable $previous = null
    ) {
        $this->problemValue = $problemValue;

        parent::__construct($message, $code, $previous);
    }

    public function getProblemValue()
    {
        return $problemValue;
    }

    public function setProblemValue($problemValue)
    {
        $this->problemValue = $problemValue;
    }
}
