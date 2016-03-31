<?php declare(strict_types=1);

namespace Schale\Test\Support\Mock\Model\Module;

use Schale\Annotation;
use Schale\Traits\Accessors;

/**
 * @Annotation\Model(name="article_module")
 */
class ArticleModel
{
    use Accessors;

    /**
     * @Annotation\Id()
     */
    protected $id;

    /**
     * @Annotation\Property(name="regionId", type="string", optional=true)
     */
    protected $regionId;

    /**
     * @Annotation\TypedCollection(name="tags", type="tag")
     */
    protected $tags = [];
}
