<?php declare(strict_types=1);

namespace Shale\Annotation;

use InvalidArgumentException;
use Shale\Annotation\Model;

/**
 * Use this annotation for "properties" classes for Eclipse API modules.
 *
 * Every Eclipse module contains a structure labelled "properties".
 * Usually we create a PHP class for this and annotate it.
 *
 * This annotation means you don't need to manually set the name for
 * the properties. Instead, you give the name of the Eclipse *module*.
 * So if you have an Eclipse module like this:
 *
 *     /**
 *      * @Annotation\EclipseModule(name="My-Module")
 *      *
 *     class MyClass
 *     {
 *         /**
 *          * @Annotation\Property(name="properties")
 *          *
 *         protected $properties;
 *
 *         // ...
 *     }
 *
 *  You can create a class for the "properties" structure like this:
 *
 *     /**
 *      * @Annotation\EclipseModuleProperties(of="My-Module")
 *      *
 *     class MySecondClass
 *     {
 *         // ...
 *     }
 *
 * @Annotation
 * @Target({"CLASS"})
 */
class EclipseModuleProperties extends Model
{
    public function __construct(array $values)
    {
        if (!array_key_exists('of', $values)) {
            throw new InvalidArgumentException(
                'The EclipseModuleProperties annotation must include '
                . 'an "of" argument, naming the Eclipse module these '
                . 'are the properties of.'
            );
        }

        $this->name = $values['of'] . '-Properties';
    }
}
