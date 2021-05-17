<?php

declare(strict_types=1);

namespace Shale;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use ReflectionException;
use Shale\Exception\Schema\LoadSchemaException;
use Shale\Schema\Engine as SchemaEngine;

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
     * @param $modelsPath
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
            $paths = $this->getModelFqcns($path);
            $modelFqncs = array_merge($modelFqncs, $paths);
        }

        $this->schemaEngine->loadSchemaForModels($modelFqncs);
    }

    /**
     * @param string $path
     * @return array
     */
    protected function getModelFqcns(string $path): array
    {
        $fqcns = [];
        $allFiles = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path)
        );

        $phpFiles = new RegexIterator($allFiles, '/\.php$/');
        foreach ($phpFiles as $phpFile) {
            $content = file_get_contents($phpFile->getRealPath());
            $tokens = token_get_all($content);
            $namespace = '';
            for ($index = 0; isset($tokens[$index]); $index++) {
                if (!isset($tokens[$index][0])) {
                    continue;
                }
                if (T_NAMESPACE === $tokens[$index][0]) {
                    $index += 2; // Skip namespace keyword and whitespace
                    while (isset($tokens[$index]) && is_array($tokens[$index])) {
                        $namespace .= $tokens[$index++][1];
                    }
                }
                if (T_CLASS === $tokens[$index][0]) {
                    $index += 2; // Skip class keyword and whitespace
                    $fqcns[] = $namespace.'\\'.$tokens[$index][1];
                }
            }
        }

        return $fqcns;
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
