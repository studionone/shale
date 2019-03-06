<?php declare(strict_types=1);

namespace Shale;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use Shale\Schema\Engine as SchemaEngine;

class ModelHydrator
{
    protected $schemaEngine;
    protected $modelsPath;

    public function __construct(SchemaEngine $schemaEngine, string $modelsPath)
    {
        $this->schemaEngine = $schemaEngine;
        $this->modelsPath = $modelsPath;

        $modelFqncs = $this->getModelFqcns();
        $this->schemaEngine->loadSchemaForModels($modelFqncs);
    }

    public function getModelFqcns()
    {
        if (is_null($this->modelsPath)) {
            return [];
        }
        $fqcns = [];
        $allFiles = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->modelsPath)
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

    public function hydrateFromJson(string $rootModelFqcn, $jsonObjectAsArray)
    {
        $rootModelInstance = $this->schemaEngine
                                  ->createModelInstanceFromData($rootModelFqcn, $jsonObjectAsArray);
        return $rootModelInstance;
    }
    public function serializeModelInstanceToJson($modelInstance)
    {
        return $this->schemaEngine
                    ->createDataFromModelInstance($modelInstance);
    }
}
