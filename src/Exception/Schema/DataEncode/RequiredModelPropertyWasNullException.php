<?php

declare(strict_types=1);

namespace Shale\Exception\Schema\DataEncode;

use Throwable;

/**
 * Class RequiredModelPropertyWasNullException
 *
 * @package Shale\Exception\Schema\DataEncode
 */
class RequiredModelPropertyWasNullException extends RequiredValueMissingException
{
    /**
     * RequiredModelPropertyWasNullException constructor.
     *
     * @param string $propertyNameInModel
     * @param string $propertyNameInTransport
     * @param $problemData
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $propertyNameInModel,
        string $propertyNameInTransport,
        $problemData,
        int $code = 0,
        Throwable $previous = null
    ) {
        $message = (
            'The property "' .  $propertyNameInModel . '" (named "' .
            $propertyNameInTransport . '" in transport) is required, but was ' .
            'null on the model instance given'
        );

        parent::__construct($message, $problemData, $code, $previous);
    }
}
