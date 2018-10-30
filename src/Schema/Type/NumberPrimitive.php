<?php declare(strict_types=1);

namespace Shale\Schema\Type;

use Shale\Interfaces\Schema\SchemaNamedTypeInterface;
use Shale\Schema\Type\Validate\NumberValidate;

class NumberPrimitive implements SchemaNamedTypeInterface
{
    use Validator;

    protected $validators = [
        NumberValidate::class
    ];

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'number';
    }
}
