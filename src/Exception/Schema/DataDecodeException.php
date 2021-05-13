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
    /** @var mixed|null */
    public $problemData;

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
     * @return mixed|null
     */
    public function getProblemData()
    {
        return $this->problemData;
    }

    /**
     * @param $problemData
     */
    public function setProblemData($problemData)
    {
        $this->problemData = $problemData;
    }
}
