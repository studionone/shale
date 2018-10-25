<?php declare(strict_types=1);

namespace Shale\DataMapper;

use Shale\Interfaces\DataMapper\DataMapperInterface;
use Shale\ModelHydrator;

class APIJson implements DataMapperInterface
{
    protected $modelHydrator;
    protected $responseModelFqcn;

    public function __construct(ModelHydrator $modelHydrator, string $responseModelFqcn)
    {
        $this->modelHydrator = $modelHydrator;
        $this->responseModelFqcn = $responseModelFqcn;
    }

    public function map($data)
    {
        return $this->modelHydrator
                    ->hydrateFromJson($this->responseModelFqcn, $data);
    }
}
