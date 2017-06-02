<?php declare(strict_types=1);

namespace Shale\Test\Support\Mock\Model;

use Shale\Annotation;
use Shale\Traits\Accessors;

/**
 * @Annotation\Model(name="model_with_unannotated_property")
 */
class ModelWithUnannotatedProperty
{
    use Accessors;

    protected $unannotatedProperty;

    /**
     * @Annotation\Property(name="annotatedProperty", type="string")
     */
    protected $annotatedProperty;
}
