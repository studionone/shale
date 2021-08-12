<?php

declare(strict_types=1);

namespace Shale\Schema\Type;

use Shale\Interfaces\Schema\SchemaNamedTypeInterface;
use cebe\markdown\Markdown as MarkdownParser;

/**
 * Class MarkDown
 *
 * @package Shale\Schema\Type
 */
class MarkDown extends StringPrimitive implements SchemaNamedTypeInterface
{
    use Validator;

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'markdown';
    }

    /**
     * @return MarkdownParser
     */
    public function getParser(): MarkdownParser
    {
        return new MarkdownExtraParser();
    }
}
