<?php

declare(strict_types=1);

namespace Shale\Schema;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use RegexIterator;

/**
 * A utility class for giving the fully-qualified class name (FQCN) of
 * all classes in a directory.
 *
 * This is just a helper class for convenience, as almost any code using
 * Shale will likely need something like this for use with
 * $schemaEngine->loadSchemaForModels([..]). The alternative is manually
 * enumerating each model class's FQCN.
 *
 * This is unrelated to the Shale\AnnotationLoader class.
 */
class FqcnLoader
{
    /**
     * Given a path to a directory, give the fully-qualified class name
     * (FQCN) of each PHP class in that directory or its
     * sub-directories.
     *
     * Implementation adapted from:
     * http://stackoverflow.com/questions/22761554/php-get-all-class-names-inside-a-particular-namespace
     *
     * @return string[] An array with each class's FQCN as a string.
     */
    public function getFqcnsForPath(string $path): array
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
}
