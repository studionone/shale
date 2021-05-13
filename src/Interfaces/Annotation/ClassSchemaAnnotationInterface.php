<?php

declare(strict_types=1);

namespace Shale\Interfaces\Annotation;

use Doctrine\Common\Annotations\Reader as AnnotationReader;
use Shale\Interfaces\Schema\SchemaTypeInterface;

/**
 * Interface ClassSchemaAnnotationInterface
 *
 * @package Shale\Interfaces\Annotation
 */
interface ClassSchemaAnnotationInterface
{
    /**
     * @param string $annotatedClassFqcn
     * @param AnnotationReader $annotationReader
     * @return SchemaTypeInterface
     */
    public function giveClassSchema(
        string $annotatedClassFqcn,
        AnnotationReader $annotationReader
    ): SchemaTypeInterface;
}
