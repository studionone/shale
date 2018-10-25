<?php declare(strict_types=1);

namespace Shale\Schema\Type;

use Shale\Interfaces\Schema\SchemaNamedTypeInterface;
use Shale\Schema\Type\Validate\StringValidate;
use Shale\Schema\Type\Validate\UrlSchemeValidate;

class Url implements SchemaNamedTypeInterface
{
    use Validator;

    protected $validators = [
        StringValidate::class,
        UrlSchemeValidate::class
    ];

    protected $exceptionMessage = 'number (integer or float)';

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'url';
    }
}
