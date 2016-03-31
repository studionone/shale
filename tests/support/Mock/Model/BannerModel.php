<?php declare(strict_types=1);  

namespace Schale\Test\Support\Mock\Model;

use Schale\Annotation;
use Schale\Traits\Accessors;

/**
 * @Annotation\Model(name="banner")
 */
class BannerModel
{
    use Accessors;

    /**
     * @Annotation\Property(name="imageUrl", type="string")
     */
    protected $imageUrl;

    /**
     * @Annotation\Property(name="title", type="string", optional=true)
     */
    protected $title;
}
