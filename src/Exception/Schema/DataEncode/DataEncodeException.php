<?php

declare(strict_types=1);

namespace Shale\Exception\Schema\DataEncode;

use Throwable;
use Shale\Exception\Schema\SchemaException;

/**
 * Class DataEncodeException
 *
 * @package Shale\Exception\Schema\DataEncode
 */
class DataEncodeException extends SchemaException
{
    /** @var mixed */
    public mixed $problemValue;

    /**
     * DataEncodeException constructor.
     *
     * @param string $message
     * @param mixed|null $problemValue
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = '',
        $problemValue = null,
        int $code = 0,
        Throwable $previous = null
    ) {
        $this->problemValue = $problemValue;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed
     */
    public function getProblemValue(): mixed
    {
        return $this->problemValue;
    }

    /**
     * @param $problemValue
     * @return void
     */
    public function setProblemValue($problemValue): void
    {
        $this->problemValue = $problemValue;
    }
}
