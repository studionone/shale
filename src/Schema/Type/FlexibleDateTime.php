<?php declare(strict_types=1);

namespace Shale\Schema\Type;

use Shale\Interfaces\Schema\SchemaNamedTypeInterface;
use Shale\Schema\Type\Validate\FlexibleDateTimeValidate;

class FlexibleDateTime implements SchemaNamedTypeInterface
{
    use Validator;

    protected $validators = [
        FlexibleDateTimeValidate::class,
    ];

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'flexibleDateTime';
    }
}
