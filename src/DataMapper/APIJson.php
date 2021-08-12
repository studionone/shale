<?php

declare(strict_types=1);

namespace Shale\DataMapper;

use Shale\Interfaces\DataMapper\DataMapperInterface;
use Shale\ModelHydrator;

/**
 * Class APIJson
 *
 * @package Shale\DataMapper
 */
class APIJson implements DataMapperInterface
{
    /** @var ModelHydrator */
    protected ModelHydrator $modelHydrator;

    /** @var string */
    protected string $responseModelFqcn;

    /**
     * APIJson constructor.
     *
     * @param ModelHydrator $modelHydrator
     * @param string $responseModelFqcn
     */
    public function __construct(
        ModelHydrator $modelHydrator,
        string $responseModelFqcn
    ) {
        $this->modelHydrator = $modelHydrator;
        $this->responseModelFqcn = $responseModelFqcn;
    }

    /**
     * @param mixed $data
     * @return mixed
     */
    public function map(mixed $data): mixed
    {
        return $this->modelHydrator->hydrateFromJson($this->responseModelFqcn, $data);
    }
}
