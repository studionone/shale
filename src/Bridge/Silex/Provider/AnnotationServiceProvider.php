<?php
namespace Schale\Bridge\Silex\Provider;

use Silex\{Application, ServiceProviderInterface};
use Doctrine\Common\Annotations\{AnnotationReader, AnnotationRegistry};

/**
 * Register our annotations for use.
 *
 * This sets up our annotations system on ExampleApp init.
 *
 * We're using Doctrine\Common's annotation system. For documentation,
 * see http://doctrine-common.readthedocs.org/en/latest/reference/annotations.html
 *
 * For the annotation classes themselves, see Schale\Annotation\.
 */
class AnnotationServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers our annotation setup. Currently hard-coded, but will be
     * configurable in the future
     * TODO: Make this configurable
     *
     * @param Silex\App $pimple
     */
    public function register(Application $app)
    {
        /**
         * registerLoader takes a callable that matches spl_autoload_register
         * XXX: The callable **must** fail silently!
         *
         * Note that the callable may be called multiple times for an
         * annotation: first with the non-fully-qualified name, and then with
         * the fully-qualified name.
         *
         * The full-qualification of names may be derived based on "use"
         * statements in the file where the annotation is used. eg if a class
         * file has "use Schale\Annotation;", then Annotation\Model will be
         * valid for annotating within that file.
         */
        AnnotationRegistry::registerLoader(function($baseClassname) {
            $baseDir = __DIR__ . '/../../../Annotation';
            $classname = ltrim($baseClassname, '\\');

            // Checks we're using the correct FQCN
            //
            // This means we only load annotations which are fully qualified.
            if (strpos($classname, 'Schale\Annotation\\') === 0) {
                return true;
            }

            return false;
        });

        $reader = new AnnotationReader();
        $app['annotation_reader'] = $reader;
    }

    public function boot(Application $app)
    {
    }
}
