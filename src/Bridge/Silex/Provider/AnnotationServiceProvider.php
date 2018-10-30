<?php
namespace Shale\Bridge\Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Shale\AnnotationLoader;

/**
 * Register our annotations for use.
 *
 * This sets up our annotations system on Silex/Flint app init.
 *
 * We're using Doctrine\Common's annotation system. For documentation,
 * see http://doctrine-common.readthedocs.org/en/latest/reference/annotations.html
 *
 * For the annotation classes themselves, see Shale\Annotation\.
 */
class AnnotationServiceProvider implements ServiceProviderInterface
{
    protected $annotationLoader;
    protected $annotationReader;

    public function __construct(
        array $annotationFqcnsAndFilePaths=[],
        bool $includeBuiltInAnnotations=AnnotationLoader::INCLUDE_BUILTIN_ANNOTATIONS,
        bool $annotationsMustBeLoadedExplicitly=AnnotationLoader::ANNOTATIONS_MUST_BE_LOADED_EXPLICITLY
    ) {
        $this->annotationLoader = new AnnotationLoader(
            $annotationFqcnsAndFilePaths,
            $includeBuiltInAnnotations,
            $annotationsMustBeLoadedExplicitly
        );

        $this->annotationReader = new AnnotationReader();
    }

    public function getAnnotationLoader(): AnnotationLoader
    {
        return $this->annotationLoader;
    }

    public function getAnnotationReader(): AnnotationReader
    {
        return $this->annotationReader;
    }

    /**
     * Registers the annotation loader in the Doctrine Annotations
     * registry, and adds the annotation reader to the app.
     *
     * @param Silex\App $pimple
     */
    public function register(Application $app)
    {
        AnnotationRegistry::registerLoader(
            [$this->annotationLoader, 'load']
        );

        $app['annotation_reader'] = $this->annotationReader;
    }

    public function boot(Application $app)
    {
    }
}
