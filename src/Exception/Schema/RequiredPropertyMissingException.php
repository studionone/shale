<?php
namespace Shale\Exception\Schema;

use Throwable;

class RequiredPropertyMissingException extends RequiredDataMissingException
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
            $propertyNameInModel . '" in model) is required, but was missing ' .
            'from the object data given'
        );
        parent::__construct($message, $problemData, $code, $previous);
    }
}
