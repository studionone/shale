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
    protected SchemaEngine $schemaEngine;

    /**
     * ModelHydrator constructor.
     *
     * @param SchemaEngine $schemaEngine
     * @param string|array $modelsPath
     * @throws LoadSchemaException
     * @throws ReflectionException
     */
    public function __construct(SchemaEngine $schemaEngine, string|array $modelsPath)
    {
        $this->schemaEngine = $schemaEngine;

        $modelsPath = $modelsPath ?? [];
        $modelsPath = is_array($modelsPath)
            ? $modelsPath
            : [$modelsPath];

        $models = [];
        foreach ($modelsPath as $path) {
            $paths = ClassLoader::getClassesInPath($path);
            $models = array_merge($models, $paths);
        }

        $this->schemaEngine->loadSchemaForModels($models);
    }

    /**
     * @param string $rootModelFqcn
     * @param $jsonObjectAsArray
     * @return mixed
     */
    public function hydrateFromJson(string $rootModelFqcn, $jsonObjectAsArray): mixed
    {
        return $this
            ->schemaEngine
            ->createModelInstanceFromData($rootModelFqcn, $jsonObjectAsArray);
    }

    /**
     * @param $modelInstance
     * @return mixed
     */
    public function serializeModelInstanceToJson($modelInstance): mixed
    {
        return $this
            ->schemaEngine
            ->createDataFromModelInstance($modelInstance);
    }
}
