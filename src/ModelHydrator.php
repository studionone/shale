<?php

declare(strict_types=1);

namespace Shale;

use ReflectionException;
use Shale\Exception\Schema\LoadSchemaException;
use Shale\Schema\Engine as SchemaEngine;
use Shale\Util\ClassLoader;

/**
 * Class ModelHydrator
 *
 * @package Shale
 */
class ModelHydrator
{
    /** @var SchemaEngine */
    protected $schemaEngine;

    /**
     * ModelHydrator constructor.
     *
     * @param SchemaEngine $schemaEngine
     * @param string|array|null $modelsPath
     * @throws LoadSchemaException
     * @throws ReflectionException
     */
    public function __construct(SchemaEngine $schemaEngine, $modelsPath)
    {
        $this->schemaEngine = $schemaEngine;

        $modelsPath = $modelsPath ?? [];
        $modelsPath = is_array($modelsPath)
            ? $modelsPath
            : [$modelsPath];

        $modelFqncs = [];
        foreach ($modelsPath as $path) {
            $paths = ClassLoader::getClassesInPath($path);
            $modelFqncs = array_merge($modelFqncs, $paths);
        }

        $this->schemaEngine->loadSchemaForModels($modelFqncs);
    }

    /**
     * @param string $rootModelFqcn
     * @param $jsonObjectAsArray
     * @return mixed
     */
    public function hydrateFromJson(string $rootModelFqcn, $jsonObjectAsArray)
    {
        return $this
            ->schemaEngine
            ->createModelInstanceFromData($rootModelFqcn, $jsonObjectAsArray);
    }

    /**
     * @param $modelInstance
     * @return mixed
     */
    public function serializeModelInstanceToJson($modelInstance)
    {
        return $this
            ->schemaEngine
            ->createDataFromModelInstance($modelInstance);
    }
}
