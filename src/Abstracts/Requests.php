<?php

namespace Jikan\Abstracts;

/**
 * Class Requests
 *
 * @package Jikan\Abstracts
 */
abstract class Requests
{

    private $path;
    private $id;

    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setPath(string $path)
    {
        $this->path = $path;

        return $this;
    }

    public function getID()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setID(int $id)
    {
        $this->id = $id;

        return $this;
    }
}
