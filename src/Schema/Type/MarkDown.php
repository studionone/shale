<?php declare(strict_types=1);

namespace Shale\Schema\Type;

use Shale\Interfaces\Schema\SchemaNamedTypeInterface;
use cebe\markdown\Markdown as MarkdownParser;
use Shale\ThirdParty\Markdown\MarkdownExtraParser;

class MarkDown extends StringPrimitive implements SchemaNamedTypeInterface
{
    use Validator;

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'markdown';
    }

    /**
     * @return MarkdownParser
     */
    public function getParser()
    {
        return new MarkdownExtraParser();
    }
}
