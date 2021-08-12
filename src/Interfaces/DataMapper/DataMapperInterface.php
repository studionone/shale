<?php

declare(strict_types=1);

namespace Shale\Interfaces\DataMapper;

/**
 * Interface DataMapperInterface
 *
 * @package Shale\Interfaces\DataMapper
 */
interface DataMapperInterface
{
    /**
     * @param mixed $data
     * @return mixed
     */
    public function map(mixed $data): mixed;
}
