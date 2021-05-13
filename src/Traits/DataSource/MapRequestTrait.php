<?php

declare(strict_types=1);

namespace Shale\Traits\DataSource;

use Result\Result;
use Shale\Interfaces\DataMapper\DataMapperInterface;

/**
 * Trait MapRequestTrait
 *
 * @package Shale\Traits\DataSource
 */
trait MapRequestTrait
{
    /**
     * @param DataMapperInterface $dataMapper
     * @param Result $result
     * @return Result
     */
    public function mapResult(DataMapperInterface $dataMapper, Result $result): Result
    {
        $result = $result->remapOk(
            function ($result) use ($dataMapper) {
                return [
                    'status' => $result['status'],
                    'responseModel' => $dataMapper->map($result['data']),
                ];
            }
        );
        
        return $result;
    }
}
