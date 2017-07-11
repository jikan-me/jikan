<?php

namespace Jikan\Get;

class Manga
{

    public $id;


    public function __construct($id) {
        $this->id = $id;


        return $this;
    }


    public function characters() {
        return $this;
    }

}