<?php declare(strict_types=1);  

namespace Schale\Test\Support\Mock\Model;

use Schale\Annotation;
use Schale\Traits\Accessors;

/**
 * @Annotation\Model(name="payload")
 */
class PayloadModel
{
    use Accessors;
    /**
     * @Annotation\MixedCollection(name="modules", typeField="type", optional=true)
     */
    protected $modules = [];

    /**
     * @Annotation\TypedCollection(name="pageBanners", type="banner", optional=true)
     */
    protected $pageBanners = [];
}
