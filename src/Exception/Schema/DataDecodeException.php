<?php

declare(strict_types=1);

namespace Shale\Exception\Schema;

use Throwable;

/**
 * Class DataDecodeException
 *
 * @package Shale\Exception\Schema
 */
class DataDecodeException extends SchemaException
{
    /** @var mixed */
    public mixed $problemData;

    /**
     * DataDecodeException constructor.
     *
     * @param string $message
     * @param mixed|null $problemData
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = "",
        $problemData = null,
        int $code = 0,
        Throwable $previous = null
    ) {
        $this->problemData = $problemData;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed
     */
    public function getProblemData(): mixed
    {
        return $this->problemData;
    }

    /**
     * @param $problemData
     * @return void
     */
    public function setProblemData($problemData): void
    {
        $this->problemData = $problemData;
    }
}
