<?php declare(strict_types=1);

namespace Shale\Schema\Type;

use Shale\Interfaces\Schema\SchemaNamedTypeInterface;
use Shale\Schema\Type\Validate\StringValidate;

class StringPrimitive implements SchemaNamedTypeInterface
{
    use Validator;

    protected $validators = [
        StringValidate::class
    ];

    protected $exceptionMessage = 'string';

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'string';
    }
}
