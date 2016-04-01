<?php declare(strict_types=1);
namespace Schale\Test\Support\Mock\Model;

use Schale\Annotation;
use Schale\Traits\Accessors;

/**
 * @Annotation\Model(name="tag")
 */
class TagModel
{
    use Accessors;

    /**
     * @Annotation\Id()
     */
    protected $id;

    /**
     * @Annotation\Property(name="name", type="string")
     */
    protected $name;
}
