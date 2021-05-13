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
    protected $modelHydrator;

    /** @var string */
    protected $responseModelFqcn;

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
     * @param $data
     * @return mixed
     */
    public function map($data)
    {
        return $this->modelHydrator->hydrateFromJson($this->responseModelFqcn, $data);
    }
}
