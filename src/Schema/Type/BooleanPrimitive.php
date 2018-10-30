<?php declare(strict_types=1);

namespace Shale\Schema\Type;

use Shale\Interfaces\Schema\SchemaNamedTypeInterface;
use Shale\Schema\Type\Validate\BooleanValidate;

class BooleanPrimitive implements SchemaNamedTypeInterface
{
    use Validator;

    /**
     * @var array
     */
    protected $validators = [
        BooleanValidate::class
    ];

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'boolean';
    }
}
