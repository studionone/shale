<?php declare(strict_types=1);
namespace Shale\Interfaces\Annotation;

use Shale\Interfaces\Schema\SchemaInterface;

interface PropertySchemaAnnotationInterface
{
    public function givePropertySchema($nameInModel): SchemaInterface;
}
