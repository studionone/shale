<?php declare(strict_types=1);

namespace Shale\Interfaces\Annotation;

use Doctrine\Common\Annotations\Reader as AnnotationReader;
use Shale\Interfaces\Schema\SchemaTypeInterface;

interface ClassSchemaAnnotationInterface
{
    public function giveClassSchema(
        string $annotatedClassFqcn,
        AnnotationReader $annotationReader
    ): SchemaTypeInterface;
}
