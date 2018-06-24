<?php

namespace Jikan\Abstracts;

/**
 * Class Helper
 *
 * @package Jikan\Abstracts
 */
abstract class Helper extends \Jikan\Abstracts\Container
{

    /**
     * Helper constructor.
     */
    public function __construct()
    {
        return $this;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $this->offsetSet($key, $value);

        return $this;
    }

    /**
     * @param $key
     *
     * @return mixed|null
     */
    public function get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * @return mixed
     */
    abstract public function build();

}