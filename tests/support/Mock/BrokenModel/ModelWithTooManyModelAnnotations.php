<?php declare(strict_types=1);

namespace Shale\Test\Support\Mock\BrokenModel;

use Shale\Annotation;

/**
 * This is a model class which has two different Model annotations.
 *
 * We use this class in integration/schema/SchemaTest.php to check that
 * our schema system throws the correct exception when given a class
 * like this.
 *
 * @Annotation\Model(name="broken_model_1")
 * @Annotation\Model(name="broken_model_2")
 */
class ModelWithTooManyModelAnnotations
{
}
