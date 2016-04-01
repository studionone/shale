<?php declare(strict_types=1);

namespace Schale\Interfaces\Annotation;

use Doctrine\Common\Annotations\Reader as AnnotationReader;
use Schale\Interfaces\Schema\SchemaTypeInterface;

interface ClassSchemaAnnotationInterface
{
    public function giveClassSchema(
        string $annotatedClassFqcn,
        AnnotationReader $annotationReader
    ): SchemaTypeInterface;
}
