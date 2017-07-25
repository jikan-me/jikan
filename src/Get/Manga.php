<?php

namespace Jikan\Get;

use Jikan\Lib\Parser\MangaParse;


class Manga extends Get
{


    public function __construct($id) {
        $this->id = $id;
        $this->parser = new MangaParse("t");

        return $this;
    }

    public function episodes() {

        return $this;
    }

    public function characters() {
        return $this;
    }

}