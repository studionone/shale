<?php declare(strict_types=1);

namespace Shale\Test\Support\Mock\BrokenModel;

/**
 * This is a model class which has no Model annotation.
 *
 * We use this class in integration/schema/SchemaTest.php to check that
 * our schema system throws the correct exception when given a class
 * like this.
 */
class ModelMissingModelAnnotation
{
}
