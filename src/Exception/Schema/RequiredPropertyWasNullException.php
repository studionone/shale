<?php
namespace Shale\Exception\Schema;

use Throwable;

class RequiredPropertyWasNullException extends RequiredDataMissingException
{
    public function __construct(
        string $propertyNameInTransport,
        string $propertyNameInModel,
        $problemData,
        $code = 0,
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
