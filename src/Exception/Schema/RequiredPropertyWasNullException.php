<?php

declare(strict_types=1);

namespace Shale\Exception\Schema;

use Throwable;

/**
 * Class RequiredPropertyWasNullException
 *
 * @package Shale\Exception\Schema
 */
class RequiredPropertyWasNullException extends RequiredDataMissingException
{
    /**
     * RequiredPropertyWasNullException constructor.
     *
     * @param string $propertyNameInTransport
     * @param string $propertyNameInModel
     * @param $problemData
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $propertyNameInTransport,
        string $propertyNameInModel,
        $problemData,
        int $code = 0,
        Throwable $previous = null
    ) {
        $message = (
            'The property "' .  $propertyNameInTransport . '" (named "' .
            $propertyNameInModel . '" in model) is required, but the data ' .
            'given for its value was null'
        );

        parent::__construct($message, $problemData, $code, $previous);
    }
}
