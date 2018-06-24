<?php

namespace Jikan\Abstracts;

abstract class Requests
{

    protected $id;
    private $path;

    public function getPath()
    {
        return $this->path;
    }

    public function setPath(string $path)
    {
        $this->path = $path;

        return $this;
    }

    public function getID()
    {
        return $this->id;
    }

    public function setID(int $id)
    {
        $this->id = $id;

        return $this;
    }
}