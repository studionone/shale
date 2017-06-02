<?php declare(strict_types=1);

namespace Shale;

use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 *
 * You will likely use this somewhere in your application by creating an
 * instance with the options you'd like (or the defaults), and then
 * registering this instance as a loader on the Doctrine Annotations
 * registry. For example:
 *
 *     $myLoader = new AnnotationLoader();
 *     AnnotationLoader([$myLoader, 'load']);
 *
 */
class AnnotationLoader
{
    const INCLUDE_BUILTIN_ANNOTATIONS = true;
    const DO_NOT_INCLUDE_BUILTIN_ANNOTATIONS = false;

    const ANNOTATIONS_MUST_BE_LOADED_EXPLICITLY = true;
    const ANNOTATIONS_WILL_BE_LOADED_IMPLICITLY = false;


    protected $fqcnsAndFilePaths;
    protected $includeBuiltInAnnotations;
    protected $annotationsMustBeLoadedExplicitly;

    public static function getBuiltinAnnotationFqcnAndPath()
    {
        /*
         * This is added to the FQCN/filepath array if the option
         * INCLUDE_BUILTIN_ANNOTATIONS is given on instance construction
         * (the default behaviour).
         *
         * This is used by the loader to map the base FQCN for built-in
         * Shale annotations (ie 'Schale\Annotation\') to the actual
         * on-disk path of the 'Annotation' directory in the Shale
         * package (eg '.../vendor/studionone/schale/src/Annotation/').
         *
         * Depending on other options, the loader may actually require
         * the annotation class from the filepath, or it may just check
         * if the annotation FQCN is within a known annotation
         * namespace.
         */
        return ['Shale\\Annotation\\', __DIR__ . '/Annotation'];
    }
    
    public function __construct(
        array $fqcnsAndFilePaths=[],
        bool $includeBuiltInAnnotations=self::INCLUDE_BUILTIN_ANNOTATIONS,
        bool $annotationsMustBeLoadedExplicitly=self::ANNOTATIONS_MUST_BE_LOADED_EXPLICITLY
    ) {
        $this->fqcnsAndFilePaths = $fqcnsAndFilePaths;
        $this->includeBuiltInAnnotations = $includeBuiltInAnnotations;

        if ($includeBuiltInAnnotations === self::INCLUDE_BUILTIN_ANNOTATIONS) {
            $this->fqcnsAndFilePaths[] = self::getBuiltinAnnotationFqcnAndPath();
        }

        $this->annotationsMustBeLoadedExplicitly = $annotationsMustBeLoadedExplicitly;
    }

    /**
     * Load a given annotation class (or at least check it exists).
     *
     * This is designed to be given to
     * Doctrine\Common\Annotations\AnnotationRegistry::registerLoader()
     * as a callable. AnnotationRegistry::registerLoader() takes a
     * callable that matches spl_autoload_register
     *
     * XXX NOTE that this must fail silently (only returning true or
     * false) because of how it's used by AnnotationRegistry.
     *
     * Note that the callable may be called multiple times for an
     * annotation: first with the non-fully-qualified name, and then with
     * the fully-qualified name.
     *
     * The full-qualification of names may be derived based on "use"
     * statements in the file where the annotation is used. eg if a class
     * file has "use Shale\Annotation;", then Annotation\Model will be
     * valid for annotating within that file.
     */
    public function load($baseClassname)
    {
        /*
         * TODO Update this to respect the
         * $annotationsMustBeLoadedExplicitly option in __construct().
         */ 

        $classname = ltrim($baseClassname, '\\');

        // Check we're using an allowed FQCN
        //
        // This means we only load annotations which are fully qualified.
        foreach ($this->fqcnsAndFilePaths as $fqcnAndFilePath) {
            $fqcn = $fqcnAndFilePath[0];

            if (strpos($classname, $fqcn) === 0) {
                return true;
            }
        }

        return false;
    }
}
