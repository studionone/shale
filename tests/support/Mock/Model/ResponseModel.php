<?php declare(strict_types=1);

namespace Schale\Test\Support\Mock\Model;

use Schale\Annotation;
use Schale\Traits\Accessors;

/**
 * @Annotation\Model(name="response")
 */
class ResponseModel
{
    use Accessors;

    /**
     * @Annotation\Property(name="status", type="number")
     */
    protected $status;

    /**
     * @Annotation\Property(name="message", type="string")
     */
    protected $message;

    /**
     * @Annotation\Property(name="payload", type="payload")
     */
    protected $payload;
}
