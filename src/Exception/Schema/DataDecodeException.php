<?php declare(strict_types=1);

namespace Shale\Exception\Schema;

use Throwable;

class DataDecodeException extends SchemaException
{
    public $problemData;

    public function __construct(
        string $message = "",
        $problemData = null,
        int $code = 0,
        Throwable $previous = null
    ) {
        $this->problemData = $problemData;

        parent::__construct($message, $code, $previous);
    }

    public function getProblemData()
    {
        return $problemData;
    }

    public function setProblemData($problemData)
    {
        $this->problemData = $problemData;
    }
}
