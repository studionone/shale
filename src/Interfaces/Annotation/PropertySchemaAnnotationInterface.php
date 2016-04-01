<?php declare(strict_types=1);
namespace Schale\Interfaces\Annotation;

use Schale\Interfaces\Schema\SchemaInterface;

interface PropertySchemaAnnotationInterface
{
    public function givePropertySchema($nameInModel): SchemaInterface;
}
