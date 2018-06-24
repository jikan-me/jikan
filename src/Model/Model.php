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

    /**
     * @param      $class
     * @param      $key_array
     * @param      $value
     * @param null $key
     */
    public function insert($class, $key_array, $value, $key = null)
    {
        if (property_exists('Jikan\Model\\'.$class, $key)) {
            if (is_null($key)) {
                $this->{$key_array}[] = $value;
            } else {
                $this->{$key_array}[$key] = $value;
            }
        }
    }
}
