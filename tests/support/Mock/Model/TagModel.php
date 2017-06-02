<?php declare(strict_types=1);
namespace Shale\Test\Support\Mock\Model;

use Shale\Annotation;
use Shale\Traits\Accessors;

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
