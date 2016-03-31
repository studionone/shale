<?php declare(strict_types=1);

namespace Schale\Test\Support\Mock\Model;

use Schale\Annotation;
use Schale\Traits\Accessors;

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
