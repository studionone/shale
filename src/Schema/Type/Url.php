<?php

declare(strict_types=1);

namespace Shale\Schema\Type;

use Shale\Interfaces\Schema\SchemaNamedTypeInterface;
use Shale\Schema\Type\Validate\StringValidate;
use Shale\Schema\Type\Validate\UrlSchemeValidate;

/**
 * Class Url
 *
 * @package Shale\Schema\Type
 */
class Url implements SchemaNamedTypeInterface
{
    use Validator;

    /**
     * @var string[]
     */
    protected array $validators = [
        StringValidate::class,
        UrlSchemeValidate::class,
    ];

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'url';
    }
}
