<?php declare(strict_types=1);

namespace Schale\Test\Support\Mock\BrokenModel;

use Schale\Annotation;

/**
 * This is a model class which has two different annotations on a
 * property.
 *
 * We use this class in integration/schema/SchemaTest.php to check that
 * our schema system throws the correct exception when given a class
 * like this.
 *
 * @Annotation\Model(name="broken_model")
 */
class ModelWithTooManyPropertyAnnotations
{
    /**
     * @Annotation\Property(name="name", type="string")
     * @Annotation\Property(name="name2", type="string")
     */
    protected $name;
}
