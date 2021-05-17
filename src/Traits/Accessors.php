<?php

declare(strict_types=1);

namespace Shale\Traits;

use BadMethodCallException;
use InvalidArgumentException;

/**
 * Trait Accessors
 * It gives magic setters and getters for the properties on your class
 *
 * @package Shale\Traits
 */
trait Accessors
{
    /**
     * @param $method
     * @param $args
     */
    public function __call($method, $args)
    {
        if (
            !preg_match('/(?P<accessor>set|get)(?P<property>[A-Z][a-zA-Z0-9]*)/', $method, $match)
            || !property_exists(static::class, $match['property'] = lcfirst($match['property']))
        ) {
            throw new BadMethodCallException(sprintf(
                "'%s' does not exist in '%s'.",
                $method,
                get_class($this)
            ));
        }

        switch ($match['accessor']) {
            case 'get':
                // Short circuit and return property directly
                return $this->{$match['property']};
            case 'set':
                if (!$args) {
                    throw new InvalidArgumentException(sprintf("'%s' requires an argument value.", $method));
                }
                $this->{$match['property']} = $args[0];
        }

        return $this;
    }
}
