<?php declare(strict_types=1);

namespace Shale\Interfaces\Schema;

use Shale\Schema\TypeRegistry;

interface SchemaTypeInterface
{
    /**
     * @param mixed $data
     * @param TypeRegistry $typeRegistry
     * @return mixed
     */
    public function getValueFromData($data, TypeRegistry $typeRegistry);
}
