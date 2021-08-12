<?php

declare(strict_types=1);

namespace Shale\Annotation;

/**
 * @Annotation
 */
class IdUuid extends Property
{
    /**
     * IdUuid constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->name = 'id';
        $this->type = 'uuid';
    }
}
