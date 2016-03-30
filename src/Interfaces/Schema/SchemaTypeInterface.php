<?php declare(strict_types=1);

namespace Schale\Interfaces\Schema;

use Schale\Schema\TypeRegistry;

interface SchemaTypeInterface
{
    /**
     * @param mixed $data
     * @param TypeRegistry $typeRegistry
     * @return mixed
     */
    public function getValueFromData($data, TypeRegistry $typeRegistry);
}
