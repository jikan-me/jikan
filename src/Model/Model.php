<?php

namespace Jikan\Model;

/**
 * Class Model
 *
 * @package Jikan\Model
 */
abstract class Model
{

    /**
     * @param $class
     * @param $key
     * @param $value
     */
    public function set($class, $key, $value)
    {
        if (property_exists('Jikan\Model\\'.$class, $key)) {
            $this->{$key} = $value;
        }
    }

    /**
     * @param $class
     * @param $key
     *
     * @return mixed
     */
    public function get($class, $key)
    {
        if (property_exists('Jikan\Model\\'.$class, $key)) {
            return $this->{$key};
        }
    }
}
