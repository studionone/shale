<?php

declare(strict_types=1);

namespace Shale\Interfaces\Annotation;

use Shale\Interfaces\Schema\SchemaInterface;

/**
 * Interface PropertySchemaAnnotationInterface
 *
 * @package Shale\Interfaces\Annotation
 */
interface PropertySchemaAnnotationInterface
{
    /**
     * @param string $nameInModel
     * @return SchemaInterface
     */
    public function givePropertySchema(string $nameInModel): SchemaInterface;
}
